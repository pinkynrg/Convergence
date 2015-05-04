<?php namespace Convergence\Http\Controllers;

	define("CONVERGENCE_HOST", "198.154.99.22:1088");
	define("CONVERGENCE_DB", "Elettric80Inc");	
	define("CONVERGENCE_USER", "saa");
	define("CONVERGENCE_PASS", "V09Wd519");

	define("LOCAL_HOST", "127.0.0.1");	
	define("LOCAL_DB", "convergence2");	
	define("LOCAL_USER", "root");
	define("LOCAL_PASS", "dir2004caz");

	class ImportController extends Controller {

		private function logger($success, $errors, $label) {
			echo "[".date("Y-m-d H:i:s")."] <span style='color:green'> Added ".$success." ".$label."</span> ";
			echo $errors != 0 ? "<span style='color:red'>".$errors." errors made during the process.</span>" : "";
			echo "<br>";
		}

		private function truncate($table) {

			$result = false;

			$query = "DELETE FROM ".$table." WHERE 1 = 1";

			echo "[".date("Y-m-d H:i:s")."]";

			if (mysqli_query($this->conn, $query) === TRUE) {
				echo "<span style='color:green'> ".$table." table truncated successfully. </span>";
				$result = true;
			}
			else {
				echo "<span style='color:red'> ".$table." table truncation failed. </span>";
			}

			echo "<br>";

			return $result;
		}	

		private function findMatchingContactId($ticket) {

			$id = false;

			if ($ticket['Cellphone_Contact']) {
				$query = mssql_query("SELECT Id_Contact FROM [Contact] WHERE CellPhone LIKE '%".$ticket['Cellphone_Contact']."%'");
				$result = mssql_fetch_array($query, MSSQL_ASSOC);
				$id = $result['Id_Contact'];
			}

			if (!$id && $ticket['Name_Contact']) {
				$ticket['Cellphone_Contact'] = strtolower($ticket['Cellphone_Contact']);
				$query = mssql_query("SELECT Id_Contact FROM [Contact] WHERE LOWER(CAST(Name AS VARCHAR(50))) LIKE '%".$ticket['Name_Contact']."%'");
				$result = mssql_fetch_array($query, MSSQL_ASSOC);
				$id = $result['Id_Contact'];
			}

			if (!$id && $ticket['Email_Contact']) {
				$ticket['Email_Contact'] = strtolower($ticket['Email_Contact']);
				$query = mssql_query("SELECT Id_Contact FROM [Contact] WHERE LOWER(CAST(Email AS VARCHAR(50))) LIKE '%".$ticket['Email_Contact']."%'");
				$result = mssql_fetch_array($query, MSSQL_ASSOC);
				$id = $result['Id_Contact'];
			}

			return $id ? $id : null;
		}


		private function importCustomers() {

			$table = 'customers';

			$query = mssql_query('SELECT * FROM [dbo].[Customers]');
			$successes = $errors = 0;


			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $customers[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($customers as $c) {

					$c['Id_Employee_Account_Manager'] = $c['Id_Employee_Account_Manager'] == '' ? 'NULL' : "'".$c['Id_Employee_Account_Manager']."'";
					$c['ZipCode'] = $c['ZipCode'] == 'UNKNOWN' ? '' : $c['ZipCode'];
					
					$query_contact = mssql_query("SELECT id_Contact FROM Contact WHERE CAST(Contact.Name AS VARCHAR(50)) = '".$c['Contact']."'");
					$result = mssql_fetch_array($query_contact, MSSQL_ASSOC);
					$c['Main_Contact_Id'] = ($result['id_Contact'] == '' || $c['Contact'] == '') ? 'NULL' : $result['id_Contact'];

					$query = "INSERT INTO customers (id, company_name, address, country, city, state, zip_code, airport, account_manager_id,main_contact_id,plant_requirment,created_at,updated_at) 
						VALUES ('".$c['Id']."','".$c['Customer']."','".$c['Address']."','".$c['Country']."','".$c['City']."','".$c['State']."',
						'".$c['ZipCode']."','".$c['Airport']."',".$c['Id_Employee_Account_Manager'].",".$c['Main_Contact_Id'].",'".$c['Plant_Requirement']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					
					if (mysqli_query($this->conn, $query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importEmployees() {

			$table = 'employees';

			$query = mssql_query('SELECT * FROM [dbo].[Employees]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $employees[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($employees as $e) {

					if (strpos(trim($e['First_name'])," ") === false) {
						$first_name = $e['Name'];
						$last_name = $e['Last_name'];
					}
					else {
						$exploded = explode(" ",$e['First_name']);
						$first_name = $exploded[0];
						$last_name = implode(" ",array_slice($exploded,1));
					}

					$query = "INSERT INTO employees (id,first_name,last_name,department_id,title_id,phone,extension,speed_dial,email,created_at,updated_at) 
					VALUES ('".$e['Id']."','".$first_name."','".$last_name."','".$e['Department']."','".$e['Title']."','".$e['Phone']."','".$e['Extension']."','".$e['Speed_Dial']."','".$e['Email']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}

		}

		private function importEmployeeDepartments() {

			$table = 'departments';

			$query = mssql_query('SELECT * FROM [dbo].[Employee_Departments]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $departments[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($departments as $d) {

					$query = "INSERT INTO departments (id,name,created_at,updated_at) VALUES ('".$d['Id']."','".$d['Department']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importEmployeeTitles() {

			$table = 'titles';
			
			$query = mssql_query('SELECT * FROM [dbo].[Employee_Titles]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $titles[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($titles as $t) {

					$query = "INSERT INTO titles (id,name,created_at,updated_at) VALUES ('".$t['Id']."','".$t['Title']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importTickets() {

			$table = 'tickets';

			$query = mssql_query("SELECT * FROM [dbo].[Tickets] WHERE datalength(Name_Contact) != 0");
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $tickets[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($tickets as $t) {

					$contact_id = $this->findMatchingContactId($t);
					$ticket_post = str_replace('&#65533;','',strip_tags($t['Ticket_Post']));

					$query = "INSERT INTO tickets (id,title,post,creator_id,assignee_id,status_id,priority_id,division_id,equipment_id,customer_id,contact_id,created_at,updated_at) 
					 		  VALUES ('".$t['Id']."','".$t['Ticket_Title']."','".$ticket_post."','".$t['Creator']."','".$t['Id_Assignee']."','".$t['Status']."','".$t['Priority']."','".$t['Id_System']."','".$t['Id_Equipment']."','".$t['Id_Customer']."','".$contact_id."','".$t['Date_Creation']."','".$t['Date_Update']."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importStatus() {

			$table = 'statuses';

			$query = mssql_query('SELECT * FROM [dbo].[Ticket_Status]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $statuses[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($statuses as $s) {

					$query = "INSERT INTO statuses (id,name) 
							  VALUES ('".$s['Id']."','".$s['Status']."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}

		}

		private function importPriorities() {

			$table = 'priorities';

			$query = mssql_query('SELECT * FROM [dbo].[Priority]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $priorities[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($priorities as $p) {

					$query = "INSERT INTO priorities (id,name) 
							  VALUES ('".$p['Id']."','".$p['Priority']."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importDivisions() {

			$table = 'divisions';

			$query = mssql_query('SELECT * FROM [dbo].[System_Type]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $divisions[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($divisions as $d) {

					$query = "INSERT INTO divisions (id,name) 
							  VALUES ('".$d['Id']."','".$d['Type']."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}

		}

		private function importEquipments() {

			$table = 'equipments';
			$successes = $errors = 0;
			
			$query = mssql_query('SELECT * FROM [dbo].[CustomersEquipment]');
			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $equipments[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($equipments as $e) {

					$query = "INSERT INTO equipments (id,name, cc_number, serial_number, equipment_type_id, notes, warranty_expiration, customer_id) 
							  VALUES ('".$e['Id']."','".$e['NickName']."','".$e['CC_Number']."','".$e['Serial_Number']."','".$e['Equipment_Type']."','".$e['Notes']."','".$e['WarrantyExpiration']."','".$e['CompanyId']."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}

		}

		private function importEquipmentTypes() {

			$table = 'equipment_types';

			$query = mssql_query('SELECT * FROM [dbo].[Equipment_Types]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $equipmentTypes[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($equipmentTypes as $e) {

					$query = "INSERT INTO equipment_types (id,name) 
							  VALUES ('".$e['Id']."','".$e['Name']."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importContacts() {

			$table = 'contacts';

			$query = mssql_query('SELECT * FROM [dbo].[Contact]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $contacts[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($contacts as $c) {

					$temp = preg_replace('/[^\p{L}\p{N}\s]/u', '', $c['Name']);
					$temp = explode(" ",strtolower($temp));
					$c['Name'] = '';

					foreach ($temp as $part) 
						$c['Name'] .= ucfirst($part)." ";

					$c['Name'] = trim($c['Name']);

					$c['Email'] = strtolower($c['Email']);

					if ($c['Name'] != '') {				

						$query = "INSERT INTO contacts (id,customer_id,name,phone,cellphone,email) 
								  VALUES ('".$c['Id_Contact']."','".$c['Id_Customer']."','".$c['Name']."','".$c['Phone']."','".$c['CellPhone']."','".$c['Email']."')";
						
						if (mysqli_query($this->conn,$query) === TRUE) {
							$successes++;
						}
						else {
							$errors++;
						}
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importPosts() {

			$table = 'posts';
			$successes = $errors = 0;

			$query = mssql_query('SELECT * FROM [dbo].[Posts]');

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $posts[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($posts as $p) {

					$creation_date = $p['Date_Creation']." ".$p['Time'];

					$query = "INSERT INTO posts (id,ticket_id,post,author_id,is_public,created_at,updated_at) 
							  VALUES ('".$p['Id']."','".$p['Id_Ticket']."','".$p['Post']."','".$p['Author']."','".$p['Post_Public']."','".$creation_date."','".date("Y-m-d H:i:s")."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importUsers() {

			$table = 'users';
			$successes = $errors = 0;

			if ($this->truncate($table)) {

				$query_customer_users = mssql_query("SELECT C.Id_Contact, LOWER(LTRIM(RTRIM(CAST(CU.Customer_User AS VARCHAR(100))))) AS Customer_User, CU.Customer_Password
								  FROM Contact AS C
								  INNER JOIN Customer_User_Login AS CU ON 
								  ((RTRIM(LTRIM(CAST(C.Name AS VARCHAR(100)))) = RTRIM(LTRIM(CU.Customer_Name))+' '+RTRIM(LTRIM(CU.Customer_Last_Name))
								  OR RTRIM(LTRIM(CAST(C.Name AS VARCHAR(100)))) = RTRIM(LTRIM(CU.Customer_Last_Name))+' '+RTRIM(LTRIM(CU.Customer_Name))
								  OR RTRIM(LTRIM(CAST(C.Email AS VARCHAR(100)))) = RTRIM(LTRIM(CAST(CU.email_customer_user AS VARCHAR(100)))))
								  AND C.Id_Customer = CU.Company_Id)
								  ORDER BY Id_Contact");

				while ($row = mssql_fetch_array($query_customer_users, MSSQL_ASSOC)) $users[] = $row;

				mssql_free_result($query_customer_users);

				foreach ($users as $u) {

					$query = "INSERT INTO users (owner_id,owner_type,username,password,created_at,updated_at) 
							  VALUES ('".$u['Id_Contact']."','Convergence\\\Models\\\Contact','".$u['Customer_User']."','".$u['Customer_Password']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$users = [];

				$query_employee_logins = mssql_query("SELECT * FROM Login");

				while ($row = mssql_fetch_array($query_employee_logins, MSSQL_ASSOC)) $users[] = $row;

				mssql_free_result($query_employee_logins);

				foreach ($users as $u) {

					$query = "INSERT INTO users (owner_id,owner_type,username,password,created_at,updated_at) 
							  VALUES ('".$u['Employee_Id']."','Convergence\\\Models\\\Employee','".$u['User_name']."','".$u['User_password']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		public function __construct() {

			if (!mssql_connect(CONVERGENCE_HOST,CONVERGENCE_USER,CONVERGENCE_PASS)) {
				die("error connecting to ".CONVERGENCE_HOST);
			}

			if (!mssql_query('USE '.CONVERGENCE_DB)) {
				die("error opening db ".CONVERGENCE_DB);
			}

			$this->conn = mysqli_connect(LOCAL_HOST, LOCAL_USER,LOCAL_PASS, LOCAL_DB) or 
				die("error connecting to localhost");

			mysqli_select_db($this->conn, LOCAL_DB) or 
				die("error opening db ".LOCAL_DB);
		}

		public function import($target = null) {

			if ($target) {
				
				$method = 'import'.ucfirst($target);
				
				if (method_exists($this, $method)) {
					$this->{$method}();
				}
				else {
					die('the method '.$method.' doesn\'t exists');
				}
			}
			else {
				$this->importCustomers();
				$this->importEmployees();
				$this->importEmployeeDepartments();
				$this->importEmployeeTitles();
				$this->importTickets();
				$this->importStatus();
				$this->importPriorities();
				$this->importDivisions();
				$this->importEquipments();
				$this->importEquipmentTypes();
				$this->importContacts();
				$this->importPosts();
				$this->importUsers();
			}
			
			

			

		}
	}

?>
