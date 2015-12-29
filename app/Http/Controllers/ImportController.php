<?php namespace App\Http\Controllers;

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	ini_set('memory_limit', '5G');
	ini_set('max_execution_time', 3000);	
	error_reporting(-1);

	define("CONVERGENCE_HOST", "198.154.99.22:1088");
	define("CONVERGENCE_DB", "Elettric80Inc");	
	define("CONVERGENCE_USER", "saa");
	define("CONVERGENCE_PASS", "V09Wd519");

	define("LOCAL_HOST", "127.0.0.1");	
	define("LOCAL_DB", "convergence2");	
	define("LOCAL_USER", "root");
	define("LOCAL_PASS", "dir2004caz");
	define("CONSTANT_GAP_CONTACTS",500);

	define("ELETTRIC80_COMPANY_ID",1);

	class ImportController extends Controller {

		private function logger($success, $errors, $label) {
			echo "[".date("Y-m-d H:i:s")."] <span style='color:green'> Added ".$success." ".$label."</span> ";
			echo $errors != 0 ? "<span style='color:red'>".$errors." errors made during the process.</span>" : "";
			echo "<br>";
		}

		private function truncate($table) {

			$result = false;

			$query1 = "SET foreign_key_checks = 0";
			$query2 = "DELETE FROM ".$table." WHERE 1 = 1";
			$query3 = "SET foreign_key_checks = 1";

			echo "[".date("Y-m-d H:i:s")."]";

			if (mysqli_query($this->conn, $query1) === TRUE && mysqli_query($this->conn, $query2) === TRUE && mysqli_query($this->conn, $query3) === TRUE) {
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

		private function findCompanyPersonId($person_id) {
			$query = "SELECT * FROM company_person WHERE person_id = $person_id";
			$result = mysqli_query($this->conn, $query);
			$record = mysqli_fetch_array($result);
			return is_numeric($record['id']) ? $record['id'] : 'NULL';
		}

		private function trimAndNullIfEmpty($row) {
			
			foreach ($row as $key => $value) {
				$row[$key] = trim($row[$key]);
				$row[$key] = strtolower($row[$key]) == 'na' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == 'n/a' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == 'test' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == 'void' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == 'test - void' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == 'tba' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == 'tbd' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == 'unknown' ? '' : $row[$key];
				$row[$key] = strtolower($row[$key]) == '' ? 'NULL' : "\"".$row[$key]."\"";
			}

			return $row;
		}

		private function importCompanies() {

			$table = 'companies';

			$query = mssql_query('SELECT * FROM [dbo].[Customers]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $companies[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				$query = "INSERT INTO ".$table." (id, name, address, country, city, state, zip_code, connection_type_id, support_type_id, created_at,updated_at) 
						VALUES ('".ELETTRIC80_COMPANY_ID."','Elettric80 - Chicago','8100 Monticello Ave','United States','Chicago','Illinois', '60076',NULL,NULL,'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					
					if (mysqli_query($this->conn, $query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}

				foreach ($companies as $c) {

					$c = $this->trimAndNullIfEmpty($c);

					$c['Connect_Option'] = $c['Connect_Option'] == "'A'" ? "'1'" : "'2'";
					$c['Id_Support_Type'] = $c['Id_Support_Type'] == '"0"' ? 'NULL' : $c['Id_Support_Type'];

					$query = "INSERT INTO ".$table." (id, name, address, country, city, state, zip_code, connection_type_id, support_type_id, created_at,updated_at) 
						VALUES (".$c['Id'].",".$c['Customer'].",".$c['Address'].",".$c['Country'].",".$c['City'].",".$c['State'].",
						".$c['ZipCode'].",".$c['Connect_Option'].",".$c['Id_Support_Type'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					
					if (mysqli_query($this->conn, $query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importCompanyMainContacts() {

			$table = 'company_main_contact';

			$query = mssql_query("SELECT * FROM [dbo].[Customers] WHERE Contact != ''");
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $companies[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($companies as $c) {

					$c = $this->trimAndNullIfEmpty($c);

					$query_contact = mssql_query("SELECT id_Contact FROM Contact WHERE CAST(Contact.Name AS VARCHAR(50)) = ".$c['Contact']);
					$result = mssql_fetch_array($query_contact, MSSQL_ASSOC);
					$c['Main_Contact_Id'] = ($result['id_Contact'] == '' || $c['Contact'] == '') ? 'NULL' : $result['id_Contact'] + CONSTANT_GAP_CONTACTS;

					$company_person_id = $this->findCompanyPersonId($c['Main_Contact_Id']);
					
					if ($c['Main_Contact_Id'] != 'NULL') {
						$query = "INSERT INTO ".$table." (company_id, main_contact_id) 
							VALUES (".$c['Id'].",".$company_person_id.")";

						if (mysqli_query($this->conn, $query) === TRUE) {
							$successes++;
						}
						else {
							echo $query."<br>";
							echo("Error description: " . mysqli_error($this->conn))."<br>";
							$errors++;
						}
					}
				}				
			}

			$this->logger($successes,$errors,$table);

		}

		private function importCompanyAccountManagers() {

			$table = 'company_account_manager';

			$query = mssql_query("SELECT * FROM [dbo].[Customers] WHERE Id_Employee_Account_Manager IS NOT NULL ");
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $companies[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($companies as $c) {
					
					$c = $this->trimAndNullIfEmpty($c);

					$company_person_id = $this->findCompanyPersonId($c['Id_Employee_Account_Manager']);

					$query = "INSERT INTO ".$table." (company_id, account_manager_id) 
						VALUES (".$c['Id'].",".$company_person_id.")";
					
					if (mysqli_query($this->conn, $query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}				
			}

			$this->logger($successes,$errors,$table);

		}

		private function importPeople() {
			$table = 'people';

			if ($this->truncate($table) && $this->truncate('company_person')) {
				$this->importEmployees();
				$this->importContacts();
			}
		}

		private function importEmployees() {

			$table = 'people';

			$query = mssql_query('SELECT * FROM [dbo].[Employees]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $employees[] = $row;

			mssql_free_result($query);

			foreach ($employees as $e) {

				if (strpos(trim($e['First_name'])," ") === false) {
					$e['First_Name'] = trim($e['Name']);
					$e['Last_Name'] = trim($e['Last_name']);
				}
				else {
					$exploded = explode(" ",trim($e['First_name']));
					$e['First_Name'] = trim($exploded[0]);
					$e['Last_Name'] = trim(implode(" ",array_slice($exploded,1)));
				}
				
				$e['Email'] = filter_var($e['Email'], FILTER_VALIDATE_EMAIL) ? strtolower($e['Email']) : "";
				$e['Phone'] = str_replace(array("1-","+1"),"",$e['Phone']);
				$e['Phone'] = str_replace(array(".","-"," ","(",")"),"",$e['Phone']);
				$e['Phone'] = (strlen($e['Phone']) < 10 || strlen($e['Phone']) > 10) ? "" : $e['Phone'];

				$e = $this->trimAndNullIfEmpty($e);

				$query = "INSERT INTO ".$table." (id,first_name,last_name,created_at,updated_at) 
				VALUES (".$e['Id'].",".$e['First_Name'].",".$e['Last_Name'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

				if (mysqli_query($this->conn,$query) === TRUE) {
					$query = "INSERT INTO company_person (person_id, company_id, department_id, title_id,phone,extension,cellphone,email,group_type_id) VALUES 
					(".$e['Id'].",'".ELETTRIC80_COMPANY_ID."',".$e['Department'].",".$e['Title'].",".$e['Phone'].",".$e['Extension'].",NULL,".$e['Email'].",'1')";

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						$errors++;
						
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";

						$query = "DELETE FROM ".$table." WHERE id = ".$e['Id']; 
						if (mysqli_query($this->conn,$query) === FALSE) {
							echo $query."<br>";
							echo("Error description: " . mysqli_error($this->conn))."<br>";
						}

					}
				}
				else {
					echo $query."<br>";
					echo("Error description: " . mysqli_error($this->conn))."<br>";
					$errors++;
				}

			}

			$this->logger($successes,$errors,$table);

		}

		private function importContacts() {

			$table = 'people';

			$query = mssql_query('SELECT * FROM [dbo].[Contact]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $contacts[] = $row;

			mssql_free_result($query);

			foreach ($contacts as $c) {

				$temp = trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $c['Name']));
				$temp = explode(" ",strtolower($temp));
				$c['Name'] = '';
				$c['Last_Name'] = '';

				foreach ($temp as $key => $part) 
					if ($key == 0)
						$c['Name'] .= ucfirst($part);
					else 
						$c['Last_Name'] .= ucfirst($part)." ";

				$c['Id_Contact'] = $c['Id_Contact'] == '' ? '' : $c['Id_Contact'] + CONSTANT_GAP_CONTACTS;

				$c['Phone'] = str_replace(array("1-","+1"),"",$c['Phone']);
				$c['Phone'] = str_replace(array(".","-"," ","(",")"),"",$c['Phone']);
				$c['Phone'] = (strlen($c['Phone']) < 10 || strlen($c['Phone']) > 10) ? "" : $c['Phone'];

				$c['CellPhone'] = str_replace(array("1-","+1"),"",$c['CellPhone']);
				$c['CellPhone'] = str_replace(array(".","-"," ","(",")"),"",$c['CellPhone']);
				$c['CellPhone'] = (strlen($c['CellPhone']) < 10 || strlen($c['CellPhone']) > 10) ? "" : $c['CellPhone'];

				$c['Email'] = strtolower($c['Email']);

				$c = $this->trimAndNullIfEmpty($c);

				$query = "INSERT INTO ".$table." (id,first_name,last_name,created_at,updated_at) 
						  VALUES (".$c['Id_Contact'].",".$c['Name'].",".$c['Last_Name'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
				
				if (mysqli_query($this->conn,$query) === TRUE) {

					$query = "INSERT INTO company_person (person_id,company_id,department_id,title_id,phone,extension,cellphone,email,group_type_id, created_at,updated_at) VALUES 
					(".$c['Id_Contact'].",".$c['Id_Customer'].",NULL,NULL,".$c['Phone'].",NULL,".$c['CellPhone'].",".$c['Email'].",'2','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
						$query = "DELETE FROM ".$table." WHERE id = ".$c['Id_Contact']; 
						if (mysqli_query($this->conn,$query) === FALSE) {
							echo $query."<br>";
							echo("Error description: " . mysqli_error($this->conn))."<br>";
						}
					}
				}
				else {
					echo $query."<br>";
					echo("Error description: " . mysqli_error($this->conn))."<br>";
					$errors++;
				}
			}

			$this->logger($successes,$errors,$table);

		}

		private function importDepartments() {

			$table = 'departments';

			$query = mssql_query('SELECT * FROM [dbo].[Employee_Departments]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $departments[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($departments as $d) {

					$d = $this->trimAndNullIfEmpty($d);

					$query = "INSERT INTO departments (id,name) VALUES (".$d['Id'].",".$d['Department'].")";	

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importTitles() {

			$table = 'titles';
			
			$query = mssql_query('SELECT * FROM [dbo].[Employee_Titles]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $titles[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($titles as $t) {

					$t = $this->trimAndNullIfEmpty($t);

					$query = "INSERT INTO titles (id,name) VALUES (".$t['Id'].",".$t['Title'].");";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importTickets() {

			$table = 'tickets';

			$query = mssql_query("SELECT * FROM [dbo].[Tickets] WHERE Priority != '' AND Id = 4042");
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $tickets[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($tickets as $t) {

					$t['Contact_Id'] = $this->findMatchingContactId($t);
					$t['Contact_Id'] = trim($t['Contact_Id']) == '' ? '' : trim($t['Contact_Id']) + CONSTANT_GAP_CONTACTS;
					$t['Ticket_Title'] = addSlashes(str_replace('&#65533;','',strip_tags($t['Ticket_Title'])));
					$t['Ticket_Post'] = addSlashes(str_replace('&#65533;','',strip_tags($t['Ticket_Post'])));

					$t = $this->trimAndNullIfEmpty($t);

					$creator_id = $this->findCompanyPersonId($t['Creator']);
					$assignee_id = $this->findCompanyPersonId($t['Id_Assignee']);
					$contact_id = $this->findCompanyPersonId($t['Contact_Id']);

					$query = "INSERT INTO tickets (id,title,post,creator_id,assignee_id,status_id,priority_id,division_id,equipment_id,company_id,contact_id,job_type_id,created_at,updated_at) 
					 		  VALUES (".$t['Id'].",".$t['Ticket_Title'].",".$t['Ticket_Post'].",".$creator_id.",".$assignee_id.",".$t['Status'].",".$t['Priority'].",".$t['Id_System'].",".$t['Id_Equipment'].",".$t['Id_Customer'].",".$contact_id.",".$t['Job_Type'].",".$t['Date_Creation'].",".$t['Date_Update'].")";
					

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
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

					$s = $this->trimAndNullIfEmpty($s);

					$query = "INSERT INTO statuses (id,name) 
							  VALUES (".$s['Id'].",".$s['Status'].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
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

					$p = $this->trimAndNullIfEmpty($p);

					$query = "INSERT INTO priorities (id,name) 
							  VALUES (".$p['Id'].",".$p['Priority'].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
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

					$d = $this->trimAndNullIfEmpty($d);

					$query = "INSERT INTO divisions (id,name) 
							  VALUES (".$d['Id'].",".$d['Type'].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}

		}

		private function importEquipments() {
			$this->importEquipmentsFromConvergence();
			// $this->importEquipmentsFromSap();
		}

		private function importEquipmentsFromSap()
		{
			$query = "SELECT M.*, D.`company_id`
					FROM asmacchina AS M
					INNER JOIN
						(SELECT company_id, cc_number, CONCAT('CC',LPAD(cc_number, 3, '0')) AS 'd1'
						FROM equipments
						WHERE cc_number != '' AND cc_number IS NOT NULL
						AND cc_number REGEXP '^-?[0-9]+$'
						GROUP BY cc_number) AS D ON D.d1 = M.`PROJECT_ID` COLLATE utf8_unicode_ci
					ORDER BY cc_number DESC, `PROJECT_ID`";

			$result = mysqli_query($this->conn,$query);

			while ($temp = mysqli_fetch_object($result)) $equipments[] = $temp;

			$counter = 0;

			foreach ($equipments as $equipment) {

				$query = "SELECT * FROM equipments 
						  WHERE serial_number = '".$equipment->MATERIAL."' AND CONCAT('CC',LPAD(cc_number, 3, '0')) = '".$equipment->PROJECT_ID."' COLLATE utf8_unicode_ci LIMIT 1";

				$result = mysqli_query($this->conn,$query);
				$record = mysqli_fetch_assoc($result);

				if (isset($record)) {

					$query = "UPDATE equipments 
								SET name = '".$equipment->DESCRIPTION."',
								cc_number = '".$equipment->PROJECT_ID."',
								serial_number = '".$equipment->MATERIAL."',
								company_id = '".$equipment->company_id."',
								notes = '".$equipment->DESCRIPTION."'
								WHERE id = '".$record['id']."' LIMIT 1";

					$result = mysqli_query($this->conn,$query);
				}
				else {

					$query = "INSERT INTO equipments (name, cc_number, serial_number, company_id, notes, equipment_type_id) 
				 			VALUES ('".$equipment->DESCRIPTION."','".$equipment->PROJECT_ID."','".$equipment->MATERIAL."','".$equipment->company_id."','".$equipment->DESCRIPTION."',30)";

				 	$result = mysqli_query($this->conn,$query);
				 }
			}
		}

		private function importEquipmentsFromConvergence() 
		{
			$table = 'equipments';
			$successes = $errors = 0;
			
			$query = mssql_query('SELECT * FROM [dbo].[CustomersEquipment]');
			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $equipments[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($equipments as $e) {

					$e = $this->trimAndNullIfEmpty($e);

					$query = "INSERT INTO equipments (id,name, cc_number, serial_number, equipment_type_id, notes, warranty_expiration, company_id) 
							  VALUES (".$e['Id'].",".$e['NickName'].",".$e['CC_Number'].",".$e['Serial_Number'].",".$e['Equipment_Type'].",".$e['Notes'].",".$e['WarrantyExpiration'].",".$e['CompanyId'].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
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

					// not used since there is a TBA record that can't be NULL
					//$e = $this->trimAndNullIfEmpty($e);

					$query = "INSERT INTO equipment_types (id,name) 
							  VALUES ('".$e['Id']."','".$e['Name']."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
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

					$p['Creation_Date'] = $p['Date_Creation']." ".$p['Time'];
					$p['Creation_Date'] = str_replace(".0000000","", $p['Creation_Date']);
					// echo $p['Creation_Date']."<br>";
					$p['Post'] = addSlashes(str_replace('&#65533;','',strip_tags($p['Post'])));
					$p['Post_Public'] = $p['Post_Public'] == '' ? '0' : '1';

					if ($p['Id_Customer_User'] != '') {
						$subquery1 = mssql_query("SELECT * FROM [dbo].[Customer_User_Login] WHERE Customer_Id = '".$p['Id_Customer_User']."'");
						$result1 = mssql_fetch_array($subquery1, MSSQL_ASSOC);
						$subquery2 = mssql_query("SELECT * FROM [dbo].[Tickets] WHERE Id = '".$p['Id_Ticket']."'");
						$result2 = mssql_fetch_array($subquery2, MSSQL_ASSOC);
						$subquery3 = "SELECT * FROM company_person WHERE email = '".trim($result1['email_customer_user'])."' AND company_id = '".$result2['Id_Customer']."'";
						$result = mysqli_query($this->conn,$subquery3);
						$record = mysqli_fetch_array($result);
						$author_id = $record['id'];
					}

					$p = $this->trimAndNullIfEmpty($p);

					if (!isset($author_id)) 
						$author_id = $this->findCompanyPersonId($p['Author']);

					$query = "INSERT INTO posts (id,ticket_id,post,author_id,is_public,created_at,updated_at) 
							  VALUES (".$p['Id'].",".$p['Id_Ticket'].",".$p['Post'].",".$author_id.",".$p['Post_Public'].",".$p['Creation_Date'].",'".date("Y-m-d H:i:s")."')";
					

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}

					$author_id = null;
				}

				$this->logger($successes,$errors,$table);

			}
		}

		private function importServices() {

			$table = 'services';
			$successes = $errors = 0;

			$query = mssql_query('SELECT * FROM [saa].[Service_Request]');

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $services[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($services as $s) {

					$s['Id_hotel'] = $s['Id_hotel'] == "0" ? "" : $s['Id_hotel'];

					$s['Id_contact'] = $s['Id_contact'] == '' ? '' : $s['Id_contact'] + CONSTANT_GAP_CONTACTS;

					$s = $this->trimAndNullIfEmpty($s);

					$internal_contact_id = $this->findCompanyPersonId($s['assigment_contact']);
					$external_contact_id = $this->findCompanyPersonId($s['Id_contact']);

					$query = "INSERT INTO services (company_id,internal_contact_id,external_contact_id,job_number_internal,job_number_onsite,job_number_remote,hotel_id,created_at,updated_at) 
							  VALUES (".$s['Id_company'].",".$internal_contact_id.",".$external_contact_id.",".$s['assigment_internal'].",".$s['assigment_onsite'].",".$s['remote_install_job_number'].",".$s['Id_hotel'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
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

				$query_company_users = mssql_query("SELECT C.Id_Contact, LOWER(LTRIM(RTRIM(CAST(CU.Customer_User AS VARCHAR(100))))) AS Customer_User, CU.Customer_Password
								  FROM Contact AS C
								  INNER JOIN Customer_User_Login AS CU ON 
								  ((RTRIM(LTRIM(CAST(C.Name AS VARCHAR(100)))) = RTRIM(LTRIM(CU.Customer_Name))+' '+RTRIM(LTRIM(CU.Customer_Last_Name))
								  OR RTRIM(LTRIM(CAST(C.Name AS VARCHAR(100)))) = RTRIM(LTRIM(CU.Customer_Last_Name))+' '+RTRIM(LTRIM(CU.Customer_Name))
								  OR RTRIM(LTRIM(CAST(C.Email AS VARCHAR(100)))) = RTRIM(LTRIM(CAST(CU.email_customer_user AS VARCHAR(100)))))
								  AND C.Id_Customer = CU.Company_Id)
								  ORDER BY Id_Contact");

				while ($row = mssql_fetch_array($query_company_users, MSSQL_ASSOC)) $users[] = $row;

				mssql_free_result($query_company_users);

				foreach ($users as $u) {

					$u['Id_Contact'] = $u['Id_Contact'] == '' ? '' : $u['Id_Contact'] + CONSTANT_GAP_CONTACTS;

					$u = $this->trimAndNullIfEmpty($u);

					$query = "INSERT INTO users (person_id,username,password,created_at,updated_at) 
							  VALUES (".$u['Id_Contact'].",".$u['Customer_User'].",".$u['Customer_Password'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$users = [];

				$query_employee_logins = mssql_query("SELECT * FROM Login");

				while ($row = mssql_fetch_array($query_employee_logins, MSSQL_ASSOC)) $users[] = $row;

				mssql_free_result($query_employee_logins);

				foreach ($users as $u) {

					$u = $this->trimAndNullIfEmpty($u);

					$query = "INSERT INTO users (person_id,username,password,created_at,updated_at) 
							  VALUES (".$u['Employee_Id'].",".$u['User_name'].",".$u['User_password'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		public function importConnectionTypes() {

			$table = 'connection_types';

			$successes = $errors = $updated = $deleted = 0;

			$connectionTypes = array(1 => array('1','A','Connection to site always authorized'),
									 2 => array('2','B','Connection to site needs prior approval'));

			if ($this->truncate($table)) {

				foreach ($connectionTypes as $c) {

					$c = $this->trimAndNullIfEmpty($c);

					$query = "INSERT INTO connection_types (id,name,description) 
							  VALUES (".$c[0].",".$c[1].",".$c[2].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		public function importSupportTypes() {
	
			$table = 'support_types';

			$query = mssql_query('SELECT * FROM [dbo].[Support_Types]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $supportTypes[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($supportTypes as $s) {

					$s = $this->trimAndNullIfEmpty($s);

					$query = "INSERT INTO support_types (id,name) 
							  VALUES (".$s['id'].",".$s['Type'].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		public function importJobTypes() {

			$table = 'job_types';

			$query = mssql_query('SELECT * FROM [dbo].[Job_Type]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $jobTypes[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($jobTypes as $j) {

					$j = $this->trimAndNullIfEmpty($j);

					$query = "INSERT INTO job_types (id,name) 
							  VALUES (".$j['id'].",".$j['Job_Type'].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		public function importTags() {

			$table = 'tags';

			$query = mssql_query('SELECT * FROM [dbo].[Ticket_Tag]');
			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $tags[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($tags as $t) {

					$t = $this->trimAndNullIfEmpty($t);

					$query = "INSERT INTO tags (id,name) 
							  VALUES (".$t['Id'].",".$t['Tag_Description'].")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		public function importTagTicket() {

			$table = 'tag_ticket';

			$query = mssql_query('SELECT DISTINCT Id, Orders FROM 
									(SELECT ti.Id, t1.Tag_Description as Tag1, t2.Tag_Description as Tag2, t3.Tag_Description as Tag3
									FROM Tickets ti
									LEFT JOIN Ticket_Tag t1 ON t1.Id = ti.Tag1_Id
									LEFT JOIN Ticket_Tag t2 ON t2.Id = ti.Tag2_Id
									LEFT JOIN Ticket_Tag t3 ON t3.Id = ti.Tag3_Id
									WHERE 
									Tag1_Id IS NOT NULL AND Tag1_Id != 0
									AND Tag2_Id IS NOT NULL AND Tag2_Id != 0
									AND Tag3_Id IS NOT NULL AND Tag3_Id != 0
									) d
								UNPIVOT 
									(Orders FOR Tag IN (Tag1, Tag2, Tag3)) AS unpvt;');

			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $tag_ticket[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($tag_ticket as $t) {

					$query = "SELECT * FROM tags WHERE name = '".$t['Orders']."'";

					$result = mysqli_query($this->conn,$query);
					$record = mysqli_fetch_array($result);
					$tag_id = $record[0];

					$t = $this->trimAndNullIfEmpty($t);

					$query = "INSERT INTO tag_ticket (ticket_id,tag_id) 
							  VALUES (".$t['Id'].",".$tag_id.")";
					
					if (mysqli_query($this->conn,$query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);

			}
		}

		public function fixCompanyPersonTable() {

			$table = "fix_company_person";

			$successes = $errors = $updated = $deleted = 0;

			$query = "SELECT email
							FROM company_person
							WHERE email IS NOT NULL 
							AND email != '' 
							GROUP BY email
							HAVING count(*) > 1";

			$result = mysqli_query($this->conn,$query);
			$emails = mysqli_fetch_all($result);

			foreach ($emails as $email) {
				
				$query = "SELECT person_id FROM company_person WHERE email = '".$email[0]."'";

				$result = mysqli_query($this->conn,$query);
				$record = mysqli_fetch_array($result);
				$person_id = $record[0];

				$query = "SELECT * FROM company_person WHERE email = '".$email[0]."'";

				$result = mysqli_query($this->conn,$query);
				$fixes = mysqli_fetch_all($result);

				foreach ($fixes as $fix) {
					$query = "UPDATE company_person SET person_id = ".$person_id." WHERE id = '".$fix[0]."'";
					if (mysqli_query($this->conn,$query) === TRUE) {
						$updated++;
						$successes++;
					}
					else {
						$query = "DELETE FROM company_person WHERE id = '".$fix[0]."'";
						if (mysqli_query($this->conn,$query) === TRUE) {
							$deleted++;
							$successes++;
						}
						else {
							echo $query;
							$errors++;
						}
					}
				}
			}

			$this->logger($successes,$errors,$table);
		}

		public function deleteUnusedPeople() {

			$table = "delete_unused_people";

			$successes = $errors = 0;

			$query = "SELECT p.id FROM people p
					  LEFT JOIN company_person cp ON (p.id = cp.person_id)
					  WHERE cp.id IS NULL";

			$result = mysqli_query($this->conn,$query);
			$ids = mysqli_fetch_all($result);

			foreach ($ids as $id) {
				$query = "DELETE FROM people WHERE people.id = '".$id[0]."'";
				if (mysqli_query($this->conn,$query) === TRUE) {
					$successes++;
				}
				else {
					$errors++;
				}
			}

			$this->logger($successes,$errors,$table);

		}

		public function deleteBadE80PersonCompany() {

			$table = "delete_bad_e80";

			$successes = $errors = 0;

			$query = "SELECT person_id FROM company_person 
						WHERE company_id = 1";

			$result = mysqli_query($this->conn,$query);
			$ids = mysqli_fetch_all($result);

			foreach ($ids as $id) {

				$query = "DELETE FROM company_person
							WHERE person_id = ".$id[0]." AND company_id != 1";

				if (mysqli_query($this->conn,$query) === TRUE) {
					$successes++;
				}
				else {
					$errors++;
				}
			}

			$this->logger($successes,$errors,$table);

		}

		public function setBlankMainContact() {

			$table = "set_black_main_contacts";

			$successes = $errors = 0;

			$query = "SELECT * FROM companies c
						LEFT JOIN company_main_contact cmc ON (c.id = cmc.company_id)
						WHERE cmc.company_id IS NULL";

			$result = mysqli_query($this->conn,$query);
			$ids = mysqli_fetch_all($result);

			foreach ($ids as $id) {
				$query = "INSERT INTO company_main_contact (company_id, main_contact_id)
							SELECT cp.company_id, cp.id FROM company_person cp
							WHERE cp.company_id = ".$id[0]." LIMIT 1";

				$result = mysqli_query($this->conn,$query);

				if (mysqli_query($this->conn,$query) === TRUE) {
					$successes++;
				}
				else {
					echo $query."<br>";
					$errors++;
				}
			}

			$this->logger($successes,$errors,$table);

		}

		public function importTicketsHistory() {

			$table = 'tickets_history';

			$query = mssql_query("SELECT th.*, CONVERT(VARCHAR(19), th.Date_Time, 120) as 'date_time_formatted', e.Email as 'email_changed_by', e2.Email as 'email_assignee' 
								  FROM Ticket_History th
								  LEFT JOIN Employees e ON (e.id = th.Id_User_changed_by)
								  LEFT JOIN Employees e2 ON (e2.id = th.Id_User)");

			$successes = $errors = 0;

			while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $tickets[] = $row;

			mssql_free_result($query);

			if ($this->truncate($table)) {

				foreach ($tickets as $t) {

					$query = "SELECT * FROM tickets WHERE id = '".trim($t['Id_Ticket'])."'";
					$result = mysqli_query($this->conn,$query);
					$ti = mysqli_fetch_assoc($result);
					if (count($ti)) $ti = $this->trimAndNullIfEmpty($ti);

					if (count($ti)) {

						$query = "SELECT * FROM company_person WHERE email = '".trim($t['email_changed_by'])."'";
						$result = mysqli_query($this->conn,$query);
						$changer = mysqli_fetch_assoc($result);
						if (count($changer)) $changer = $this->trimAndNullIfEmpty($changer);
						$changer_id = isset($changer) ? $changer['id'] : 'NULL';

						$query = "SELECT * FROM company_person WHERE email = '".trim($t['email_assignee'])."'";
						$result = mysqli_query($this->conn,$query);
						$assignee = mysqli_fetch_assoc($result);
						if (count($assignee)) $assignee = $this->trimAndNullIfEmpty($assignee);
						$assignee_id = isset($assignee) ? $assignee['id'] : $ti['assignee_id'];

						$t = $this->trimAndNullIfEmpty($t);

						$query = "INSERT INTO tickets_history (ticket_id,changer_id,title,post,creator_id,assignee_id,status_id,priority_id,division_id,equipment_id,company_id,contact_id,job_type_id,created_at,updated_at) 
						 		  VALUES (".$t['Id_Ticket'].",".$changer_id.",".$ti['title'].",".$ti['post'].",".$ti['creator_id'].",".$assignee_id.",".$t['Id_Status'].",".$t['Id_Priority'].",".$t['Id_Division'].",".$ti['equipment_id'].",".$ti['company_id'].",".$ti['contact_id'].",".$ti['job_type_id'].",".$t['date_time_formatted'].",".$t['date_time_formatted'].")";
						 		  
						if (mysqli_query($this->conn,$query) === TRUE) {
							$successes++;
						}
						else {
							echo $query."<br>";
							echo("Error description: " . mysqli_error($this->conn))."<br>";
							$errors++;
						}
					}
				}

				$this->logger($successes,$errors,$table);
			}
		}

		public function importPictures() {

			$people = array();
			$query = "SELECT id, first_name, last_name FROM people WHERE image IS NULL OR image = ''";
			$result = mysqli_query($this->conn,$query);
			while ($row = mysqli_fetch_array($result)) $people[] = $row;

			foreach ($people as $person) {

				$host = "http://srv-e80int2/_layouts/searchresults.aspx?k=".$person['first_name']."%20".$person['last_name']."&u=http%3A%2F%2Fsrv-e80int2%2FLists%2FAddress%20Book";
				$username = "meli.f";
				$password = "El0414Fm";

				$process = curl_init($host);
				curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml','Content-Length: 55649'));
				curl_setopt($process, CURLOPT_HEADER, 1);
				curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
				curl_setopt($process, CURLOPT_TIMEOUT, 30);
				curl_setopt($process, CURLOPT_POST, 1);
				curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
				$return = curl_exec($process);
				curl_close($process);
			}
		}

		public function updateImageDb() {

			$people = [];

			$query = "SELECT id FROM people";
			$result = mysqli_query($this->conn,$query);
			while ($row = mysqli_fetch_array($result)) $people[] = $row;

			foreach ($people as $person) {
				if (file_exists("images/profile_pictures/".$person['id'].".gif")) {
					$query = "UPDATE people SET image = '".$person['id'].".gif' WHERE id = '".$person['id']."'";
					$result = mysqli_query($this->conn,$query);
					echo $person['id'].'.gif set as an image for person id = '.$person['id'].'<br>';
				}
			}
		}

		public function setActiveContacts() {

			$people = [];

			$query = "SELECT id FROM users";
			$result = mysqli_query($this->conn,$query);
			while ($row = mysqli_fetch_array($result)) $users[] = $row;

			foreach ($users as $user) {
				$query = "SELECT cp.id
						  FROM users u 
						  INNER JOIN people p ON p.id = u.person_id 
						  INNER JOIN company_person cp ON cp.person_id = p.id
						  WHERE u.id = ".$user['id']." LIMIT 1";
				$result = mysqli_query($this->conn,$query);
				$company_person = mysqli_fetch_array($result);
				if ($company_person) {
					$query = "UPDATE users SET active_contact_id = ".$company_person['id']." WHERE id = '".$user['id']."'";
					$result = mysqli_query($this->conn,$query);
					echo ''.$company_person['id'].' set as a company_person_id for user id = '.$user['id'].'<br>';
				}
			}
		}

		public function importPermissions() {

			$table = "permissions";
			$successes = $errors = 0;

			if ($this->truncate($table)) {

				$targets = ['permission','role','group','group-type','ticket','contact','employee','equipment','company','post','person'];
				$actions = ['create','read','read-all','update','delete'];

				$counter = 1;

				foreach ($targets as $target) {
					foreach ($actions as $action) {
						$query = "INSERT INTO permissions (id, name, display_name, description, created_at, updated_at) VALUES ($counter,'$action-$target','".ucfirst(str_replace('-',' ',$action))." ".str_replace('-',' ',$target)."','Permission to ".str_replace('-',' ',$action)." ".str_replace('-',' ',$target)."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
						if (mysqli_query($this->conn, $query) === TRUE) {
							$successes++;
						}
						else {
							echo $query."<br>";
							echo("Error description: " . mysqli_error($this->conn))."<br>";
							$errors++;
						}
						$counter++;
					}
				}
			
				$this->logger($successes,$errors,$table);
			}
		}

		public function importRoles() {

			$table = "roles";
			$successes = $errors = 0;

			if ($this->truncate($table)) {

				$targets = ['permission','role','group','group-type','ticket','contact','employee','equipment','company','post','person'];
				$actions = ['viewer','manager'];

				$counter = 1;

				foreach ($targets as $target) {
					foreach ($actions as $action) {
						$query = "INSERT INTO roles (id, name, display_name, description, created_at, updated_at) VALUES ($counter,'$target-$action','".ucfirst(str_replace('-',' ',$target))." $action','".ucfirst(str_replace('-',' ',$target))." $action role','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
						if (mysqli_query($this->conn, $query) === TRUE) {
							$successes++;
						}
						else {
							echo $query."<br>";
							echo("Error description: " . mysqli_error($this->conn))."<br>";
							$errors++;
						}
						$counter++;
					}
				}
			
				$this->logger($successes,$errors,$table);
			}
		}

		public function importPermissionRole() {
			
			$table = "permission_role";
			$successes = $errors = 0;

			$targets = ['permission','role','group','group-type','ticket','contact','employee','equipment','company','post','person'];
			$role_types = ['viewer','manager'];

			if ($this->truncate($table)) {

				foreach ($targets as $target) {
					foreach ($role_types as $role_type) {
						$query = "SELECT * FROM roles WHERE name = '$target-$role_type' LIMIT 1";

						$result = mysqli_query($this->conn, $query);
						$role = mysqli_fetch_array($result);
						
						if ($role) {
							if ($role_type == 'viewer') {
								$query = "SELECT * FROM permissions WHERE `name` LIKE '%$target' AND (`name` LIKE '%read%' OR `name` LIKE '%read-all%')";
							}
							elseif ($role_type == 'manager') {
								$query = "SELECT * FROM permissions WHERE `name` LIKE '%$target'";
							}
							
							$result = mysqli_query($this->conn, $query);
							$permissions = mysqli_fetch_all($result,MYSQLI_BOTH);

							foreach ($permissions as $permission) {
								$query = "INSERT INTO permission_role (permission_id, role_id) VALUES (".$permission['id'].",".$role['id'].")";

								if (mysqli_query($this->conn, $query) === TRUE) {
									$successes++;
								}
								else {
									echo $query."<br>";
									echo("Error description: " . mysqli_error($this->conn))."<br>";
									$errors++;
								}
							}
						}
					}
				}

				$this->logger($successes,$errors,$table);
			}
		}

		private function importGroupTypes() {

			$table = "group_types";
			$successes = $errors = 0;

			if ($this->truncate($table)) {

				$queries = ["INSERT INTO group_types (id, name, display_name, description, created_at, updated_at) VALUES (1,'employee','Employee','E80 Employee','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')",
							"INSERT INTO group_types (id, name, display_name, description, created_at, updated_at) VALUES (2,'customer','Customer','E80 Customer','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')"];
				
				foreach ($queries as $query) {
					if (mysqli_query($this->conn, $query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}
			
				$this->logger($successes,$errors,$table);

			}
		}

		public function importGroups() {

			$table = "groups";
			$successes = $errors = 0;

			if ($this->truncate($table)) {

				$queries = ["INSERT INTO groups (id, group_type_id, name, display_name, description) VALUES (1,1,'convergence-adminstrator','Convergence Administrator', 'The Administrator of Convergence')",
							"INSERT INTO groups (id, group_type_id, name, display_name, description) VALUES (2,1,'help-desk-user','Help Desk User', 'The Basic Help Desk User')"];
	
				foreach ($queries as $query) {							
				
					if (mysqli_query($this->conn, $query) === TRUE) {
						$successes++;
					}
					else {
						echo $query."<br>";
						echo("Error description: " . mysqli_error($this->conn))."<br>";
						$errors++;
					}
				}

				$this->logger($successes,$errors,$table);
			}
		}

		public function importExtraPermissions() {

			$table = "permissions";
			$successes = $errors = 0;

			$queries = [
				"INSERT INTO permissions (id, name, display_name, description, created_at, updated_at) VALUES (100,'update-role-permissions','Update role permissions','Permission to update role permissions','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')",
				"INSERT INTO permissions (id, name, display_name, description, created_at, updated_at) VALUES (101,'update-group-roles','Update group roles','Permission to update group roles','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')"
			];

			foreach ($queries as $query) {							
				
				if (mysqli_query($this->conn, $query) === TRUE) {
					$successes++;
				}
				else {
					echo $query."<br>";
					echo("Error description: " . mysqli_error($this->conn))."<br>";
					$errors++;
				}
			}

			$this->logger($successes,$errors,$table);
		}

		public function importExtraRolePermissions() {

			$table = "permission_role";
			$successes = $errors = 0;

			$queries = [
				"INSERT INTO permission_role (permission_id, role_id) VALUES (100,4)",
				"INSERT INTO permission_role (permission_id, role_id) VALUES (101,6)"
			];

			foreach ($queries as $query) {							
				
				if (mysqli_query($this->conn, $query) === TRUE) {
					$successes++;
				}
				else {
					echo $query."<br>";
					echo("Error description: " . mysqli_error($this->conn))."<br>";
					$errors++;
				}
			}

			$this->logger($successes,$errors,$table);

		}

		public function setPermissionGroups() {
			$table = "company_person";
			$successes = $errors = 0;

			$query = "UPDATE company_person SET group_id = 1 WHERE email = 'meli.f@elettric80.it'";

			if (mysqli_query($this->conn, $query) === TRUE) {
				$successes++;
			}
			else {
				echo $query."<br>";
				echo("Error description: " . mysqli_error($this->conn))."<br>";
				$errors++;
			}

			$this->logger($successes,$errors,$table);
		}

		public function importGroupRole() {
			$table = "group_role";
			$successes = $errors = 0;

			$group_roles = [
				1 => array(2,4,6,8,10,12,14,16,18,20,22),
				2 => array(10,11,12,13,16,18,20)
			];

			if ($this->truncate($table)) {
				foreach ($group_roles as $group_id => $roles) {
					
					if ($roles == "*") {
						$query = "SELECT * FROM roles";
					}
					else {
						$role_ids = implode(",",$roles);
						$query = "SELECT * FROM roles WHERE id IN ($role_ids)";
					}
					
					$result = mysqli_query($this->conn, $query);
					$roles = mysqli_fetch_all($result,MYSQLI_BOTH);

					foreach ($roles as $role) {
						$query = "INSERT INTO group_role (role_id, group_id) VALUES (".$role['id'].",".$group_id.")";

						if (mysqli_query($this->conn, $query) === TRUE) {
							$successes++;
						}
						else {
							echo $query."<br>";
							echo("Error description: " . mysqli_error($this->conn))."<br>";
							$errors++;
						}
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
				// $this->importPermissions();						// 50/50
				// $this->importRoles();							// 20/20
				// $this->importPermissionRole();					// 84/84
				// $this->importGroupTypes();						// 2/2
				// $this->importGroups();							// 1/1
				// $this->importGroupRole(); 						// 20/20
				// $this->importExtraPermissions();				// 2/2
				// $this->importExtraRolePermissions();			// 2/2
				// $this->importDepartments(); 					// 10/10
				// $this->importDivisions();						// 8/8
				// $this->importEquipmentTypes();					// 32/32
				// $this->importConnectionTypes();					// 2/2
				// $this->importSupportTypes();					// 7/7
				// $this->importJobTypes();						// 4/4
				// $this->importTags();							// 111/149 		ok 	all other are duplicates
				// $this->importPriorities();						// 5/5
				// $this->importStatus();							// 7/7
				// $this->importTitles();							// 30/30
				// $this->importCompanies();						// 93/94 		ok 	delete customer with id = 208
				// $this->importPeople();							// 393/401 		ok
				// $this->fixCompanyPersonTable();					// 93/93
				// $this->deleteBadE80PersonCompany();				// 111/111
				// $this->deleteUnusedPeople();					// 52/52
				// $this->importCompanyMainContacts();				// 15/18
				// $this->setBlankMainContact();					// 25/79 		?
				// $this->importCompanyAccountManagers();			// 73/76
				// $this->importEquipments();						// 220/220
				// $this->importTagTicket();
				// $this->importServices();
				// $this->importUsers();
				// $this->setActiveContacts();
				// $this->setPermissionGroups();					// 1/1
				
				// $this->importTickets();						// 3034/3116
				// $this->importPosts();						// 721 misses
				// $this->importTicketsHistory();
				$this->updateImageDb();
				// $this->importPictures();

			}
		}
	}

?>
