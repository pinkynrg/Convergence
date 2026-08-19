<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * Enough of a helpdesk to look at. The repository's own DatabaseSeeder builds
 * the RBAC scaffolding and one sample company, which leaves every screen empty,
 * so this fills the domain: a vendor, five customer plants, the people on both
 * sides, their machines, and three months of tickets with the posts and the
 * status history behind them.
 *
 * Every id that config/constants.php pins is honoured, because the app reads
 * those constants rather than looking anything up: company 1 is the vendor,
 * statuses are 1 to 8 in the order the icons expect, divisions are the eight
 * numbered there, and file 10000 is the default profile picture that
 * Person::profile_picture falls back to.
 *
 * The companies and the people are invented. The machinery is not: an E80 line
 * is laser guided vehicles, palletisers and a warehouse controller, so that is
 * what the tickets are about.
 */
class DemoSeeder extends Seeder
{
    /** deterministic, so the recording is the same every time it is made */
    private $seed = 20160412;

    /**
     * The low bits of a linear congruential generator barely move: taking the
     * seed modulo four returned very nearly the same value every time, which
     * put 41 of 75 tickets in one status and left the rest of the vocabulary
     * unused. The high bits are the ones with the period, so shift first.
     */
    private function next()
    {
        $this->seed = ($this->seed * 1103515245 + 12345) % 2147483648;
        return intdiv($this->seed, 65536);
    }

    private function pick(array $items)
    {
        return $items[$this->next() % count($items)];
    }

    private function between($low, $high)
    {
        return $low + ($this->next() % (int) ($high - $low + 1));
    }

    public function run()
    {
        Model::unguard();
        $this->clear();
        $this->lookups();
        $this->access();
        $contacts = $this->peopleAndCompanies();
        $this->files($contacts['e80'][0]);
        $equipment = $this->equipment();
        $this->tickets($contacts, $equipment);
        Model::reguard();
    }


    /**
     * Some migrations insert as well as create: the RBAC one brings four
     * permissions and a sample company and person with it. So this starts from
     * empty rather than from whatever migrate happened to leave, which is also
     * what makes the seeder repeatable.
     */
    private function clear()
    {
        $tables = [
            'posts', 'tag_ticket', 'ticket_links', 'tickets_history', 'tickets',
            'service_technician', 'services', 'equipment', 'files',
            'company_account_managers', 'company_main_contacts', 'activity_log',
            'users', 'company_person', 'people', 'companies',
            'escalation_profile_event', 'escalation_events', 'escalation_profiles',
            'permission_role', 'group_role', 'permissions', 'roles', 'groups',
            'group_types', 'statuses', 'priorities', 'post_statuses', 'divisions',
            'levels', 'departments', 'titles', 'support_types', 'connection_types',
            'equipment_types', 'job_types', 'tags', 'hotels', 'network_entities', 'vpns',
        ];
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * The sidebar shows an entry only if the user can() the permission behind
     * it, so the demo grants the helpdesk group all of them: the recording is
     * of the app's screens, not of its authorisation. Group ids follow
     * config/constants.php, which names 2 for employees and 10 for customers
     * and does not agree with the repository's own seeder.
     */
    private function access()
    {
        $now = date('Y-m-d H:i:s');

        foreach ([[EMPLOYEE_GROUP_TYPE_ID, 'employee', 'Employee'],
                  [CUSTOMER_GROUP_TYPE_ID, 'customer', 'Customer']] as $gt) {
            DB::table('group_types')->insert([
                'id' => $gt[0], 'name' => $gt[1], 'display_name' => $gt[2],
                'description' => $gt[2].' group type',
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach ([[DEFAULT_EMPLOYEE_GROUP_ID, EMPLOYEE_GROUP_TYPE_ID, 'e80-helpdesk', 'E80 Helpdesk'],
                  [DEFAULT_CUSTOMER_GROUP_ID, CUSTOMER_GROUP_TYPE_ID, 'basic-customer', 'Basic Customer']] as $g) {
            DB::table('groups')->insert([
                'id' => $g[0], 'group_type_id' => $g[1], 'name' => $g[2],
                'display_name' => $g[3], 'description' => $g[3],
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $permissions = [
            'create-company',
            'create-contact',
            'create-equipment',
            'create-escalation-profiles',
            'create-group',
            'create-group-type',
            'create-permission',
            'create-post',
            'create-role',
            'create-service',
            'create-ticket',
            'create-tickets',
            'create-user',
            'read-all-activity',
            'read-all-company',
            'read-all-contact',
            'read-all-equipment',
            'read-all-escalation-profiles',
            'read-all-group',
            'read-all-group-type',
            'read-all-permission',
            'read-all-post',
            'read-all-role',
            'read-all-service',
            'read-all-ticket',
            'read-all-user',
            'read-company',
            'read-contact',
            'read-equipment',
            'read-escalation-profiles',
            'read-group',
            'read-own-contact',
            'read-own-person',
            'read-permission',
            'read-person',
            'read-post',
            'read-role',
            'read-service',
            'read-ticket',
            'update-company',
            'update-contact',
            'update-customer-contact',
            'update-customer-user',
            'update-equipment',
            'update-escalation-profile-events',
            'update-escalation-profiles',
            'update-group',
            'update-group-contact',
            'update-group-roles',
            'update-group-type',
            'update-own-contact',
            'update-own-person',
            'update-own-user',
            'update-permission',
            'update-person',
            'update-post',
            'update-role',
            'update-role-permissions',
            'update-ticket',
            'update-user',
        ];

        $ids = [];
        foreach ($permissions as $i => $name) {
            $ids[] = $i + 1;
            DB::table('permissions')->insert([
                'id' => $i + 1, 'name' => $name,
                'display_name' => ucwords(str_replace('-', ' ', $name)),
                'description' => ucwords(str_replace('-', ' ', $name)),
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'helpdesk', 'display_name' => 'Helpdesk',
             'description' => 'Helpdesk engineer', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'customer', 'display_name' => 'Customer',
             'description' => 'Customer contact', 'created_at' => $now, 'updated_at' => $now],
        ]);

        foreach ($ids as $id) {
            DB::table('permission_role')->insert(['role_id' => 1, 'permission_id' => $id]);
        }
        // the customer side reads its own tickets and nothing else
        foreach (['read-ticket', 'create-ticket', 'read-post', 'create-post', 'read-company'] as $name) {
            $pid = DB::table('permissions')->where('name', $name)->value('id');
            if ($pid) {
                DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => $pid]);
            }
        }

        DB::table('group_role')->insert([
            ['group_id' => DEFAULT_EMPLOYEE_GROUP_ID, 'role_id' => 1],
            ['group_id' => DEFAULT_CUSTOMER_GROUP_ID, 'role_id' => 2],
        ]);
    }

    /** the fixed vocabularies, at the ids the constants file names */
    private function lookups()
    {
        $now = date('Y-m-d H:i:s');

        // ids 1 to 8, in the order Status::icon() switches on
        $statuses = [
            [1, 'New', 'new'], [2, 'In Progress', 'progress'],
            [3, 'Waiting For Feedback', 'wcf'], [4, 'Waiting For Parts', 'wfp'],
            [5, 'Requesting', 'request'], [6, 'Solved', 'solved'],
            [7, 'Closed', 'closed'], [8, 'Draft', 'draft'],
        ];
        foreach ($statuses as list($id, $name, $label)) {
            DB::table('statuses')->insert([
                'id' => $id, 'name' => $name, 'label' => $label,
                // every status may be moved to any other: this is a demo, not a workflow
                'allowed_statuses' => '1:2:3:4:5:6:7:8',
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach ([[1, 1, 'Line Down', 'Critical'], [2, 2, 'Degraded', 'High'],
                  [3, 3, 'Workaround', 'Medium'], [4, 4, 'Scheduled', 'Low']] as $p) {
            DB::table('priorities')->insert([
                'id' => $p[0], 'weight' => $p[1], 'name' => $p[2], 'label' => $p[3],
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // the numbering is not contiguous: 4 is unused and the constants skip it
        foreach ([[1, 'LGV', 'LGV'], [2, 'PLC', 'PLC'], [3, 'PC', 'PC'],
                  [5, 'BEMA', 'BEMA'], [6, 'Field', 'FIELD'],
                  [7, 'Others', 'OTHERS'], [8, 'Spare Parts', 'SPARE'],
                  [9, 'Reliability', 'REL']] as $d) {
            DB::table('divisions')->insert([
                'id' => $d[0], 'name' => $d[1], 'label' => $d[2],
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach ([[1, 'Draft'], [2, 'Private'], [3, 'Public']] as $s) {
            DB::table('post_statuses')->insert([
                'id' => $s[0], 'name' => $s[1], 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $simple = [
            'levels' => ['First Level', 'Second Level', 'Third Level'],
            'departments' => ['Helpdesk', 'Field Service', 'Engineering', 'Maintenance', 'Operations'],
            'support_types' => ['24/7', 'Business Hours', 'Best Effort'],
            'equipment_types' => ['LGV', 'Palletiser', 'Wrapper', 'Warehouse Controller', 'Labeller'],
            'job_types' => ['Remote', 'On Site', 'Phone'],
            'tags' => ['battery', 'navigation', 'gripper', 'label', 'network', 'firmware'],
        ];
        foreach ($simple as $table => $names) {
            foreach ($names as $i => $name) {
                DB::table($table)->insert([
                    'id' => $i + 1, 'name' => $name,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        foreach ([[1, 'VPN', 'Site to site tunnel'], [2, 'TeamViewer', 'Attended remote session'],
                  [3, 'None', 'Phone and email only']] as $c) {
            DB::table('connection_types')->insert([
                'id' => $c[0], 'name' => $c[1], 'description' => $c[2],
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach (['Helpdesk Engineer', 'Field Engineer', 'Team Leader', 'Maintenance Manager',
                  'Plant Manager', 'Account Manager'] as $i => $name) {
            // the constants pin one of these by id and nothing else looks them up
            $id = $name === 'Account Manager' ? ACCOUNT_MANAGER_TITLE_ID : $i + 1;
            DB::table('titles')->insert([
                'id' => $id, 'name' => $name, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        DB::table('escalation_profiles')->insert([
            'id' => DEFAULT_ESCALATION_PROFILE_ID, 'name' => 'Standard',
            'created_at' => $now, 'updated_at' => $now,
        ]);
    }

    /**
     * Person::profile_picture falls back to file 10000 when someone has none,
     * and the views call path() on whatever comes back, which serves the row
     * off disk. The app already ships the images these ids stand for in
     * public/resources/style, so the rows point at those rather than at
     * anything invented: without them every avatar and thumbnail is a 500.
     */
    private function files($uploader)
    {
        $now = date('Y-m-d H:i:s');
        $placeholders = [
            DEFAULT_PROFILE_PICTURE_ID => ['default.png', 'Profile picture'],
            DEFAULT_MISSING_PICTURE_ID => ['missing_thumbnail.png', 'Missing'],
            DEFAULT_COMPANY_MISSING_PICTURE_ID => ['missing_thumbnail.png', 'Missing'],
            COMPRESSED_FILE_PICTURE_ID => ['zip.png', 'Archive'],
            EMAIL_FILE_PICTURE_ID => ['email.png', 'Email'],
        ];
        foreach ($placeholders as $id => list($file, $name)) {
            DB::table('files')->insert([
                'id' => $id, 'name' => $name, 'file_path' => 'style',
                'file_name' => $file, 'file_extension' => 'png',
                'resource_type' => 'style', 'resource_id' => 0,
                'uploader_id' => $uploader,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    /** @return array{e80: int[], customer: int[]} contact ids, not person ids */
    private function peopleAndCompanies()
    {
        $now = date('Y-m-d H:i:s');

        // company 1 is the vendor itself, which is what CompanyPerson::isE80 tests
        DB::table('companies')->insert([
            'id' => ELETTRIC80_COMPANY_ID, 'name' => 'Elettric 80 Inc',
            'address' => '5200 Newport Drive', 'city' => 'Rolling Meadows',
            'state' => 'IL', 'country' => 'US', 'zip_code' => '60008',
            'group_email' => 'helpdesk@example.invalid',
            'connection_type_id' => 1, 'support_type_id' => 1,
            'escalation_profile_id' => DEFAULT_ESCALATION_PROFILE_ID,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $customers = [
            ['Northfield Dairy Co.', 'Northfield', 'MN', 1, 1],
            ['Val Brenta Beverages', 'Trento', 'IT', 1, 2],
            ['Cardinal Bottling Group', 'Sacramento', 'CA', 2, 1],
            ['Meadowlark Foods', 'Lincoln', 'NE', 1, 2],
            ['Puerto Vidal Cervecera', 'Valencia', 'ES', 3, 3],
        ];
        $companyIds = [];
        foreach ($customers as $i => list($name, $city, $state, $conn, $support)) {
            $id = 2 + $i;
            DB::table('companies')->insert([
                'id' => $id, 'name' => $name, 'city' => $city, 'state' => $state,
                'country' => in_array($state, ['IT', 'ES']) ? $state : 'US',
                'address' => $this->between(100, 9000).' Plant Road',
                'group_email' => 'maintenance@example.invalid',
                'connection_type_id' => $conn, 'support_type_id' => $support,
                'escalation_profile_id' => DEFAULT_ESCALATION_PROFILE_ID,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $companyIds[] = $id;
        }

        $e80 = [
            ['Dario', 'Fontanesi', 1, 1], ['Ilaria', 'Beltrami', 1, 1],
            ['Marco', 'Ruggeri', 2, 2], ['Sonia', 'Callegari', 3, 3],
            ['Peter', 'Vanhoof', 2, 2], ['Grace', 'Okonjo', 1, 1],
        ];
        $e80Contacts = [];
        foreach ($e80 as list($first, $last, $dept, $title)) {
            $person = DB::table('people')->insertGetId([
                'first_name' => $first, 'last_name' => $last,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $e80Contacts[] = DB::table('company_person')->insertGetId([
                'person_id' => $person, 'company_id' => ELETTRIC80_COMPANY_ID,
                'department_id' => $dept, 'title_id' => $title,
                'group_type_id' => EMPLOYEE_GROUP_TYPE_ID,
                'group_id' => DEFAULT_EMPLOYEE_GROUP_ID,
                'phone' => '+1 847 555 '.$this->between(1000, 9999),
                'email' => strtolower($first.'.'.$last).'@example.invalid',
                'division_ids' => (string) $this->pick([1, 2, 3, 5, 6]),
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $customerNames = [
            ['Hank', 'Ostergaard'], ['Rosa', 'Lindqvist'], ['Tomas', 'Berlanga'],
            ['Aimee', 'Duplessis'], ['Walter', 'Kowalczyk'], ['Nadia', 'Ferrante'],
            ['Bill', 'Trentham'], ['Yuki', 'Harada'], ['Serge', 'Mbeki'],
            ['Carla', 'Nogueira'],
        ];
        $customerContacts = [];
        foreach ($customerNames as $i => list($first, $last)) {
            $person = DB::table('people')->insertGetId([
                'first_name' => $first, 'last_name' => $last,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $customerContacts[] = DB::table('company_person')->insertGetId([
                'person_id' => $person, 'company_id' => $companyIds[$i % count($companyIds)],
                'department_id' => $this->pick([4, 5]), 'title_id' => $this->pick([4, 5]),
                'group_type_id' => CUSTOMER_GROUP_TYPE_ID,
                'group_id' => DEFAULT_CUSTOMER_GROUP_ID,
                'phone' => '+1 '.$this->between(200, 989).' 555 '.$this->between(1000, 9999),
                'email' => strtolower($first.'.'.$last).'@example.invalid',
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // one login, on the helpdesk. last_login is set so that doLogin goes
        // straight to the tickets rather than through the first-run profile page.
        $demoPerson = DB::table('company_person')->where('id', $e80Contacts[0])->value('person_id');
        DB::table('users')->insert([
            'person_id' => $demoPerson, 'active_contact_id' => $e80Contacts[0],
            'username' => 'demo', 'password' => password_hash('demo', PASSWORD_BCRYPT),
            'last_login' => $now, 'created_at' => $now, 'updated_at' => $now,
        ]);

        return ['e80' => $e80Contacts, 'customer' => $customerContacts, 'companies' => $companyIds];
    }

    /** @return array<int, int[]> equipment ids by company */
    private function equipment()
    {
        $now = date('Y-m-d H:i:s');
        $byCompany = [];
        foreach (DB::table('companies')->where('id', '!=', ELETTRIC80_COMPANY_ID)->lists('id') as $company) {
            foreach ([['LGV', 1], ['LGV', 1], ['Palletiser', 2], ['Wrapper', 3],
                      ['Warehouse Controller', 4]] as $i => list($kind, $type)) {
                $byCompany[$company][] = DB::table('equipment')->insertGetId([
                    'name' => $kind.' '.str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'cc_number' => 'CC'.$this->between(10000, 99999),
                    'serial_number' => 'E80-'.$this->between(100000, 999999),
                    'equipment_type_id' => $type, 'company_id' => $company,
                    'notes' => '', 'warranty_expiration' => '2018-06-30',
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }
        return $byCompany;
    }

    private function tickets(array $contacts, array $equipment)
    {
        $subjects = [
            ['LGV 04 stops at the wrapper handover', 'LGV 04 halts on the approach to the wrapper and reports a navigation fault. It clears on a manual restart and comes back within the hour.', 1],
            ['Battery swap bay reports wrong charge state', 'The bay shows 40% on a pack the charger says is full. Two packs affected, both from the November batch.', 1],
            ['Palletiser gripper drops the top layer', 'On the taller pallet pattern the gripper releases early and the top layer lands short. Pattern was changed last week.', 5],
            ['Label applicator skipping every fifth case', 'Applicator misses roughly one case in five on line 2. Sensor is clean and the reel is seated correctly.', 6],
            ['Warehouse controller lost the mission queue', 'After the Sunday power dip the controller came back with an empty queue and the vehicles idle.', 3],
            ['Wrapper film break alarm with no film break', 'The wrapper raises a film break alarm at random. Film is intact each time and the run continues after acknowledge.', 5],
            ['Cannot open the traffic screen since the update', 'Traffic view is blank in the browser after last week update. Other screens are fine.', 3],
            ['Spare gripper pads for the November order', 'Requesting a quote and lead time for four gripper pads, same part as the November order.', 8],
            ['LGV 02 drifts on the long straight', 'Vehicle drifts left approaching the cold store door and corrects late. Reflectors were surveyed in March.', 1],
            ['Second shift cannot log in to the HMI', 'Two operators on the second shift are refused at the HMI. First shift accounts work.', 2],
            ['Charger cabinet fan noise', 'Cabinet fan noise well above normal on charger 3. Temperature is within range so far.', 6],
            ['Pallet pattern for the new 1.5L case', 'We need a pattern for the new 1.5L case before the changeover on the 12th.', 5],
            ['Network drops between the PLC and the controller', 'Intermittent drops on the line to the warehouse controller, a few seconds at a time, several times a shift.', 2],
            ['Firmware level check before the audit', 'Please confirm the firmware level on all four vehicles ahead of the audit next month.', 9],
            ['Reflector damaged by a forklift', 'A reflector by the dispatch door was hit and is hanging loose. Vehicles are still navigating but we want it fixed.', 6],
        ];

        $replies = [
            'Connected this morning and pulled the logs. The fault is raised by the navigation and not by the safety scanner, which narrows it down.',
            'Can you confirm whether it happens on both shifts or only after the changeover?',
            'Parts are on order. Lead time is nine working days, I will confirm the tracking when it ships.',
            'We reproduced it on the test line here. It is the pattern and not the gripper, so no mechanical work needed.',
            'Reset the queue from the console and the vehicles picked up their missions again. Leaving this open until the end of the week in case it repeats.',
            'Attaching the survey from March for comparison. The reflector positions have not moved.',
            'Escalating to the field team, they are on site Thursday for the other job anyway.',
            'No recurrence since Tuesday. Closing this one, please reopen if it comes back.',
        ];

        $now = time();
        $ticketId = 0;

        // Fifteen faults across five plants over six months. The same fault at
        // two plants is not a copy: an LGV that stops at the wrapper handover
        // is the fault an LGV has, and a helpdesk sees it again and again. The
        // count is what the charts need to have a shape at all, and what makes
        // the queue read as a working queue rather than a fixture.
        foreach (range(0, 74) as $n) {
            list($title, $body, $division) = $subjects[$n % count($subjects)];
            $ticketId++;
            $company = $contacts['companies'][($n + intdiv($n, 5)) % count($contacts['companies'])];
            $reporter = $this->pick(array_values(array_filter(
                $contacts['customer'],
                function ($c) use ($company) {
                    return DB::table('company_person')->where('id', $c)->value('company_id') == $company;
                }
            )) ?: $contacts['customer']);
            $assignee = $this->pick($contacts['e80']);

            // Age decides the state: what came in this week is still open,
            // what came in months ago is closed, and the middle is where the
            // work is. Counting down, so the newest ticket has the highest
            // number, which is the order the queue is sorted in. Squared, so
            // the recent weeks are dense and the back catalogue thins out:
            // spread evenly, three quarters of the queue was closed months ago
            // and the first page showed nothing anyone was working on.
            $fraction = 1 - ($n / 75);
            $age = (int) round(180 * $fraction * $fraction) + $this->between(0, 4);
            if ($age < 10) {
                $status = $this->pick([1, 1, 5, 2]);
            } elseif ($age < 45) {
                $status = $this->pick([2, 2, 3, 4, 6]);
            } else {
                $status = $this->pick([6, 7, 7, 7]);
            }

            $opened = $now - $age * 86400 - $this->between(0, 40000);
            $stamp = date('Y-m-d H:i:s', $opened);

            DB::table('tickets')->insert([
                'id' => $ticketId, 'title' => $title, 'post' => $body,
                'creator_id' => $reporter, 'assignee_id' => $assignee,
                'status_id' => $status, 'priority_id' => $this->pick([1, 2, 2, 3, 3, 4]),
                'division_id' => $division,
                'equipment_id' => $this->pick($equipment[$company]),
                'company_id' => $company, 'contact_id' => $reporter,
                'job_type_id' => $this->pick([1, 1, 2, 3]),
                'level_id' => $this->pick([1, 1, 2, 3]),
                'emails' => '', 'created_at' => $stamp, 'updated_at' => $stamp,
            ]);

            // a thread, and the status history the statistics read
            $when = $opened;
            for ($p = 0; $p < $this->between(1, 4); $p++) {
                $when += $this->between(3, 40) * 3600;
                if ($when > $now) {
                    break;
                }
                DB::table('posts')->insert([
                    'ticket_id' => $ticketId, 'post' => $this->pick($replies),
                    'author_id' => $p % 2 ? $reporter : $assignee,
                    'status_id' => POST_PUBLIC_STATUS_ID,
                    'ticket_status_id' => $status,
                    'created_at' => date('Y-m-d H:i:s', $when),
                    'updated_at' => date('Y-m-d H:i:s', $when),
                ]);
            }

            DB::table('tickets')->where('id', $ticketId)
                ->update(['updated_at' => date('Y-m-d H:i:s', min($when, $now))]);

            $this->history($ticketId, $status, $opened, min($when, $now));
        }
    }

    /**
     * Every chart in the app is built out of tickets_history and none of them
     * reads the tickets table, so without this they are all flat lines. A row
     * is a snapshot of the ticket, and the chain through previous_id is what
     * the statistics join to themselves to find the moment a status changed:
     * one row is not a history, it takes two to make a transition.
     *
     * The route to a status is the one a helpdesk actually takes. Nothing
     * arrives solved, and nothing is closed without having been solved first.
     */
    private function history($ticketId, $finalStatus, $opened, $lastTouch)
    {
        $routes = [
            TICKET_NEW_STATUS_ID => [1],
            TICKET_IN_PROGRESS_STATUS_ID => [1, 2],
            TICKET_WFF_STATUS_ID => [1, 2, 3],
            TICKET_WFP_STATUS_ID => [1, 2, 4],
            TICKET_REQUESTING_STATUS_ID => [5],
            TICKET_SOLVED_STATUS_ID => [1, 2, 6],
            TICKET_CLOSED_STATUS_ID => [1, 2, 6, 7],
        ];
        $route = isset($routes[$finalStatus]) ? $routes[$finalStatus] : [$finalStatus];

        $ticket = DB::table('tickets')->where('id', $ticketId)->first();
        $span = max(3600, $lastTouch - $opened);
        $previous = null;

        foreach ($route as $step => $statusId) {
            // spread the steps over the ticket's life rather than bunching them
            // at its start, so the cumulative curves climb instead of jumping
            $at = $opened + (int) round($span * ($step / max(1, count($route))));
            $previous = DB::table('tickets_history')->insertGetId([
                'previous_id' => $previous,
                'ticket_id' => $ticketId,
                'changer_id' => $step === 0 ? $ticket->creator_id : $ticket->assignee_id,
                'title' => $ticket->title, 'post' => $ticket->post,
                'creator_id' => $ticket->creator_id, 'assignee_id' => $ticket->assignee_id,
                'status_id' => $statusId, 'priority_id' => $ticket->priority_id,
                'division_id' => $ticket->division_id, 'equipment_id' => $ticket->equipment_id,
                'company_id' => $ticket->company_id, 'contact_id' => $ticket->contact_id,
                'job_type_id' => $ticket->job_type_id, 'level_id' => $ticket->level_id,
                'emails' => '',
                'created_at' => date('Y-m-d H:i:s', $at),
                'updated_at' => date('Y-m-d H:i:s', $at),
            ]);
        }
    }
}
