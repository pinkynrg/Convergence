<?php namespace App\Libraries;

function logMessage($message,$type = 'normal') {
	$message = str_replace(["\t"], '', $message);
	$message = str_replace(["\n","\r"], ' ', $message);
	
	switch ($type) {
		case 'errors' : echo SET_RED; break;
		case 'successes' : echo SET_GREEN; break;
		case 'updates' : echo SET_YELLOW; break;
	}

	echo "[".date("Y-m-d H:i:s")."] ".substr($message,0,160)."\n";

	echo RESET_COLOR;
}

function trimAndNullIfEmpty($row) {
	
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
		$row[$key] = strtolower($row[$key]) == '1900-01-01' ? '' : $row[$key];
		$row[$key] = strtolower($row[$key]) == '1970-01-01' ? '' : $row[$key];

		// write rules above

		$row[$key] = strtolower($row[$key]) == '' ? 'NULL' : "\"".$row[$key]."\"";
	}

	return $row;
}

function findCompanyPersonId($person_id,$conn) {
	$query = "SELECT * FROM company_person WHERE person_id = ".$person_id;
	$result = mysqli_query($conn, $query);
	$record = mysqli_fetch_array($result);
	return is_numeric($record['id']) ? $record['id'] : 'NULL';
}

function findMatchingContactId($ticket) {

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

class ImportManager {

	private $uuid;
	private $references = array();
	private $classes;

	public function __construct() {

		$this->uuid = uniqid();

		$content = file_get_contents(LOCATION_THIS);
		$pattern = '/class ([a-zA-Z]+) extends/i';
		preg_match_all($pattern, $content, $matches);
		$this->classes = $matches[1];

		foreach ($this->classes as $class) {
			
			$class_name = "App\Libraries\\".$class;
			
			if (class_exists($class_name)) {
				$temp = new $class_name($this);
				$this->references[$temp->table_name] = $temp;
			}
			else {
				logMessage('Missing class: '.$class_name);
			}
		}

		$this->setup();
		$this->connect();
	}

	public function connect() {
		if (!@mssql_connect(CONVERGENCE_HOST,CONVERGENCE_USER,CONVERGENCE_PASS)) {
			die("Error connecting to host: ".CONVERGENCE_HOST."\n");
		}

		if (!mssql_query('USE '.CONVERGENCE_DB)) {
			die("Error connecting to database: ".CONVERGENCE_DB."\n");
		}

		$this->conn = mysqli_connect(LOCAL_HOST, LOCAL_USER,LOCAL_PASS, LOCAL_DB) or
			die('Connect Error: ' . mysqli_connect_error()."\n");

		mysqli_select_db($this->conn, LOCAL_DB) or 
			die("Error connecting to database: ".LOCAL_DB."\n");
	}

	public function addTable($class) {
		$this->references[$class->table_name] = $class;
		return $this;
	}

	public function references() {
		return $this->references;
	}

	private function setup() {
		foreach ($this->references as $value) {
			foreach ($value->dependency_names as $name) {
				if (isset($this->references[$name])) $value->dependencies[] = $this->references[$name];
			}
		}
	}

	public function import($table_name, $debug=false, $direct=false) {
		$this->uuid = uniqid();
		logMessage("========================================================");
		logMessage("NEW Import Session (UUID:".$this->uuid.")");
		logMessage("========================================================");
		if (isset($this->references[$table_name])) {
			$this->references[$table_name]->import($this->uuid,$debug,$direct);
		}
		logMessage("========================================================");
		logMessage("END Import Session (UUID:".$this->uuid.")");
		logMessage("========================================================");
	}
}

class BaseClass {
	protected $successes;
	protected $errors;
	protected $uuid;
	public $dependencies = array();

	public function __construct(ImportManager $manager) {
		$this->successes = $this->errors = 0;
		$this->manager = $manager;
		$this->manager->addTable($this);
	}

	protected function truncate() {

		$result = false;

		$query1 = "SET foreign_key_checks = 0";
		$query2 = "DELETE FROM ".$this->table_name." WHERE 1 = 1";
		$query3 = "SET foreign_key_checks = 1";

		if (mysqli_query($this->manager->conn, $query1) === TRUE && 
			mysqli_query($this->manager->conn, $query2) === TRUE && 
			mysqli_query($this->manager->conn, $query3) === TRUE) 
		{
			$result = true;
			logMessage("Table ".$this->table_name." truncated successfully!");
		}
		else {
			logMessage("Error truncating ".$this->table_name);
		}

		return $result;
	}	

	public function import($uuid,$debug=false,$direct=false) {

		$this->debug = $debug;
		
		logMessage("Begin processing table:". $this->table_name);
		logMessage("Current UUID: ".$this->uuid." | Request UUID: ".$uuid);

		if ($this->uuid != $uuid) {
			
			if (!$direct) {
				$this->importDependencies($uuid);
			}
			
			$this->importSelf();
			
			logMessage("DONE Processing:". $this->table_name);
			
			$this->uuid = $uuid;
		}
		else {
			logMessage("SKIP table: ".$this->table_name);
		}
	}

	private function importDependencies($uuid) {
		foreach ($this->dependencies as $dependency) {
			$dependency->import($uuid,$this->debug);
		}
	}
}

class Permissions extends BaseClass {
	
	public $table_name = 'permissions';
	public $dependency_names = [];

	public function importSelf() {

		if ($this->truncate()) {

			$counter = 1;

			$targets = ['permission','role','group','group-type','ticket','contact','employee','equipment','company','post','person','service'];
			$actions = ['create','read','read-all','update','delete'];

			foreach ($targets as $target) {
				foreach ($actions as $action) {
					$query = "INSERT INTO permissions (id, name, display_name, description, created_at, updated_at) VALUES ($counter,'$action-$target','".ucfirst(str_replace('-',' ',$action))." ".str_replace('-',' ',$target)."','Permission to ".str_replace('-',' ',$action)." ".str_replace('-',' ',$target)."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					if (mysqli_query($this->manager->conn, $query) === TRUE) {
						$this->successes++;
					}
					else {
						$this->errors++;
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
					$counter++;
				}
			}

			// import extra permissions

			$queries = [
				"INSERT INTO permissions (id, name, display_name, description, created_at, updated_at) VALUES (100,'update-role-permissions','Update role permissions','Permission to update role permissions','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')",
				"INSERT INTO permissions (id, name, display_name, description, created_at, updated_at) VALUES (101,'update-group-roles','Update group roles','Permission to update group roles','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')"
			];

			foreach ($queries as $query) {							
				if (mysqli_query($this->manager->conn, $query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Roles extends BaseClass {
	
	public $table_name = 'roles';
	public $dependency_names = ['permissions'];

	public function importSelf() {

		if ($this->truncate()) {

			$counter = 1;

			$targets = ['permission','role','group','group-type','ticket','contact','employee','equipment','company','post','person','service'];
			$actions = ['viewer','manager'];

			foreach ($targets as $target) {
				foreach ($actions as $action) {
					$query = "INSERT INTO roles (id, name, display_name, description, created_at, updated_at) VALUES ($counter,'$target-$action','".ucfirst(str_replace('-',' ',$target))." $action','".ucfirst(str_replace('-',' ',$target))." $action role','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
					if (mysqli_query($this->manager->conn, $query) === TRUE) {
						$this->successes++;
					}
					else {
						$this->errors++;
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
					$counter++;
				}
			}
		
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class PermissionRole extends BaseClass {
	
	public $table_name = 'permission_role';
	public $dependency_names = ['permissions','roles'];

	public function importSelf() {

		$targets = ['permission','role','group','group-type','ticket','contact','employee','equipment','company','post','person','service'];
		$role_types = ['viewer','manager'];

		if ($this->truncate()) {
		
			foreach ($targets as $target) {
				foreach ($role_types as $role_type) {
					$query = "SELECT * FROM roles WHERE name = '$target-$role_type' LIMIT 1";

					$result = mysqli_query($this->manager->conn, $query);
					$role = mysqli_fetch_array($result);
					
					if ($role) {
						if ($role_type == 'viewer') {
							$query = "SELECT * FROM permissions WHERE `name` LIKE '%$target' AND (`name` LIKE '%read%' OR `name` LIKE '%read-all%')";
						}
						elseif ($role_type == 'manager') {
							$query = "SELECT * FROM permissions WHERE `name` LIKE '%$target'";
						}
						
						$result = mysqli_query($this->manager->conn, $query);
						$permissions = mysqli_fetch_all($result,MYSQLI_BOTH);

						foreach ($permissions as $permission) {
							$query = "INSERT INTO permission_role (permission_id, role_id) VALUES (".$permission['id'].",".$role['id'].")";

							if (mysqli_query($this->manager->conn, $query) === TRUE) {
								$this->successes++;
							}
							else {
								$this->errors++;
								if ($this->debug) {
									logMessage("DEBUG: ".mysqli_error($this->manager->conn));
								}
							}
						}
					}
				}
			}

			// extra permission_role
			$queries = [
				"INSERT INTO permission_role (permission_id, role_id) VALUES (100,4)",
				"INSERT INTO permission_role (permission_id, role_id) VALUES (101,6)"
			];

			foreach ($queries as $query) {							
				if (mysqli_query($this->manager->conn, $query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}						
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class GroupTypes extends BaseClass {
	
	public $table_name = 'group_types';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		if ($this->truncate()) {

			$queries = ["INSERT INTO group_types (id, name, display_name, description, created_at, updated_at) VALUES (1,'employee','Employee','E80 Employee','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')",
						"INSERT INTO group_types (id, name, display_name, description, created_at, updated_at) VALUES (2,'customer','Customer','E80 Customer','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')"];
			
			foreach ($queries as $query) {
				if (mysqli_query($this->manager->conn, $query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Groups extends BaseClass {
	
	public $table_name = 'groups';
	public $dependency_names = ['group_types'];

	public function importSelf() {

		if ($this->truncate()) {

			$queries = ["INSERT INTO groups (id, group_type_id, name, display_name, description) VALUES (1,1,'convergence-adminstrator','Convergence Administrator', 'The Administrator of Convergence')",
						"INSERT INTO groups (id, group_type_id, name, display_name, description) VALUES (2,1,'help-desk-user','Help Desk User', 'The Basic Help Desk User')"];

			foreach ($queries as $query) {							
			
				if (mysqli_query($this->manager->conn, $query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class GroupRole extends BaseClass {
	
	public $table_name = 'group_role';
	public $dependency_names = ['roles','groups'];

	public function importSelf() {

		if ($this->truncate()) {

			$group_roles = [
				1 => array(2,4,6,8,10,12,14,16,18,20,22,24),
				2 => array(10,11,12,13,16,18,20)
			];

			foreach ($group_roles as $group_id => $roles) {
				
				if ($roles == "*") {
					$query = "SELECT * FROM roles";
				}
				else {
					$role_ids = implode(",",$roles);
					$query = "SELECT * FROM roles WHERE id IN ($role_ids)";
				}
				
				$result = mysqli_query($this->manager->conn, $query);
				$roles = mysqli_fetch_all($result,MYSQLI_BOTH);

				foreach ($roles as $role) {
					$query = "INSERT INTO group_role (role_id, group_id) VALUES (".$role['id'].",".$group_id.")";

					if (mysqli_query($this->manager->conn, $query) === TRUE) {
						$this->successes++;
					}
					else {
						$this->errors++;
						if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
					}
				}
			}
		
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Departments extends BaseClass {
	
	public $table_name = 'departments';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Employee_Departments');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $departments[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {

			foreach ($departments as $d) {

				$d = trimAndNullIfEmpty($d);

				$query = "INSERT INTO departments (id,name) VALUES (".$d['Id'].",".$d['Department'].")";	

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Divisions extends BaseClass {

	public $table_name = 'divisions';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM System_Type');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $divisions[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {

			foreach ($divisions as $d) {

				$d = trimAndNullIfEmpty($d);

				$query = "INSERT INTO divisions (id,name) 
						  VALUES (".$d['Id'].",".$d['Type'].")";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class EquipmentTypes extends BaseClass {
	
	public $table_name = 'equipment_types';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Equipment_Types');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $equipmentTypes[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {

			foreach ($equipmentTypes as $r) {

				// not used since there is a TBA record that can't be NULL
				//$e = trimAndNullIfEmpty($e);

				$query = "INSERT INTO equipment_types (id,name) 
						  VALUES ('".$r['Id']."','".$r['Name']."')";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		}

		logMessage("Successes: ".$this->successes,'successes');
		logMessage("Errors: ".$this->errors,'errors');
	}
}

class ConnectionTypes extends BaseClass {
	
	public $table_name = 'connection_types';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		if ($this->truncate()) {

			$connectionTypes = array(1 => array('1','A','Connection to site always authorized'),
									 2 => array('2','B','Connection to site needs prior approval'));

			foreach ($connectionTypes as $r) {

				$r = trimAndNullIfEmpty($r);

				$query = "INSERT INTO connection_types (id,name,description) 
						  VALUES (".$r[0].",".$r[1].",".$r[2].")";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
			
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class SupportTypes extends BaseClass {

	public $table_name = 'support_types';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Support_Types');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $supportTypes[] = $row;

		if ($this->truncate()) {
	
			foreach ($supportTypes as $s) {

				$s = trimAndNullIfEmpty($s);

				$query = "INSERT INTO support_types (id,name) 
						  VALUES (".$s['id'].",".$s['Type'].")";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class JobTypes extends BaseClass {

	public $table_name = 'job_types';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Job_Type');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				$r = trimAndNullIfEmpty($r);

				$query = "INSERT INTO job_types (id,name) 
						  VALUES (".$r['id'].",".$r['Job_Type'].")";
				
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Tags extends BaseClass {

	public $table_name = 'tags';
	public $dependency_names = [];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Ticket_Tag');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				$r = trimAndNullIfEmpty($r);
			
				$query = "INSERT INTO tags (id,name) 
						  VALUES (".$r['Id'].",".$r['Tag_Description'].")";

				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}	

class Priorities extends BaseClass {

	public $table_name = 'priorities';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Priority');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				$r = trimAndNullIfEmpty($r);
				
				$query = "INSERT INTO priorities (id,name) 
						  VALUES (".$r['Id'].",".$r['Priority'].")";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Statuses extends BaseClass {

	public $table_name = 'statuses';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Ticket_Status');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				$r = trimAndNullIfEmpty($r);
				
				$query = "INSERT INTO statuses (id,name) 
					  	VALUES (".$r['Id'].",".$r['Status'].")";
				
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			$queries = ["INSERT INTO statuses (id,name) VALUES (8,'Requesting')",
						"INSERT INTO statuses (id,name) VALUES (9,'Draft')"];

			foreach ($queries as $query) {							
			
				if (mysqli_query($this->manager->conn, $query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}				

class Titles extends BaseClass {

	public $table_name = 'titles';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Employee_Titles');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				$r = trimAndNullIfEmpty($r);
				
				$query = "INSERT INTO titles (id,name) VALUES (".$r['Id'].",".$r['Title'].")";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Companies extends BaseClass {

	public $table_name = 'companies';
	public $dependency_names = ['dummies','connection_types','support_types'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Customers');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				$r = trimAndNullIfEmpty($r);

				$r['Connect_Option'] = $r['Connect_Option'] == "'A'" ? "'1'" : "'2'";
				$r['Id_Support_Type'] = $r['Id_Support_Type'] == '"0"' ? 'NULL' : $r['Id_Support_Type'];
				
				$query = "INSERT INTO companies (id, name, address, country, city, state, zip_code, connection_type_id, support_type_id, created_at,updated_at) 
						VALUES (".$r['Id'].",".$r['Customer'].",".$r['Address'].",".$r['Country'].",".$r['City'].",".$r['State'].",
						".$r['ZipCode'].",".$r['Connect_Option'].",".$r['Id_Support_Type'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			// extra companies

			$queries = ["INSERT INTO companies (id, name, address, country, city, state, zip_code, connection_type_id, support_type_id, created_at,updated_at) 
					VALUES ('".ELETTRIC80_COMPANY_ID."','Elettric80 - Chicago','8100 Monticello Ave','United States','Chicago','Illinois', '60076',NULL,NULL,'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')"];

			foreach ($queries as $query) {							
			
				if (mysqli_query($this->manager->conn, $query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
				}
			}


			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class People extends BaseClass {

	public $table_name = 'people';
	public $dependency_names = ['dummies'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Employees');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				if (strpos(trim($r['First_name'])," ") === false) {
					$r['First_Name'] = trim($r['Name']);
					$r['Last_Name'] = trim($r['Last_name']);
				}
				else {
					$exploded = explode(" ",trim($r['First_name']));
					$r['First_Name'] = trim($exploded[0]);
					$r['Last_Name'] = trim(implode(" ",array_slice($exploded,1)));
				}
				
				$r['Email'] = filter_var($r['Email'], FILTER_VALIDATE_EMAIL) ? strtolower($r['Email']) : "";
				$r['Phone'] = str_replace(array("1-","+1"),"",$r['Phone']);
				$r['Phone'] = str_replace(array(".","-"," ","(",")"),"",$r['Phone']);
				$r['Phone'] = (strlen($r['Phone']) < 10 || strlen($r['Phone']) > 10) ? "" : $r['Phone'];

				$r = trimAndNullIfEmpty($r);
				
				$query = "INSERT INTO people (id,first_name,last_name,created_at,updated_at) 
						VALUES (".$r['Id'].",".$r['First_Name'].",".$r['Last_Name'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			$query = mssql_query('SELECT * FROM Contact');

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

				$c = trimAndNullIfEmpty($c);

				$query = "INSERT INTO people (id,first_name,last_name,created_at,updated_at) 
						  VALUES (".$c['Id_Contact'].",".$c['Name'].",".$c['Last_Name'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			$people = [];

			$query = "SELECT id FROM people";
			$result = mysqli_query($this->manager->conn,$query);
			while ($row = mysqli_fetch_array($result)) $people[] = $row;

			foreach ($people as $person) {
				

				if (file_exists(PUBLIC_FOLDER.DS."images".DS."profile_pictures".DS.$person['id'].".gif")) {
					$query = "UPDATE people SET image = '".$person['id'].".gif' WHERE id = '".$person['id']."'";
					
					if (mysqli_query($this->manager->conn,$query) === TRUE) {
						$this->successes++;
					}
					else {
						$this->errors++;
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}


class CompanyPerson extends BaseClass {

	public $table_name = 'company_person';
	public $dependency_names = ['dummies','people','companies','departments','titles','group_types','groups'];
	private $updated = 0;
	private $deleted = 0;

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Employees');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {

			$counter = 1;
	
			foreach ($table as $r) {

				if (strpos(trim($r['First_name'])," ") === false) {
					$r['First_Name'] = trim($r['Name']);
					$r['Last_Name'] = trim($r['Last_name']);
				}
				else {
					$exploded = explode(" ",trim($r['First_name']));
					$r['First_Name'] = trim($exploded[0]);
					$r['Last_Name'] = trim(implode(" ",array_slice($exploded,1)));
				}
				
				$r['Email'] = filter_var($r['Email'], FILTER_VALIDATE_EMAIL) ? strtolower($r['Email']) : "";
				$r['Phone'] = str_replace(array("1-","+1"),"",$r['Phone']);
				$r['Phone'] = str_replace(array(".","-"," ","(",")"),"",$r['Phone']);
				$r['Phone'] = (strlen($r['Phone']) < 10 || strlen($r['Phone']) > 10) ? "" : $r['Phone'];

				$r = trimAndNullIfEmpty($r);

				$query = "INSERT INTO company_person (id, person_id, company_id, department_id, title_id,phone,extension,cellphone,email,group_type_id) VALUES 
				(".$counter.",".$r['Id'].",'".ELETTRIC80_COMPANY_ID."',".$r['Department'].",".$r['Title'].",".$r['Phone'].",".$r['Extension'].",NULL,".$r['Email'].",'1')";


				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
					$counter++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			$query = mssql_query('SELECT * FROM Contact');

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

				$c = trimAndNullIfEmpty($c);

				$query = "INSERT INTO company_person (id,person_id,company_id,department_id,title_id,phone,extension,cellphone,email,group_type_id, created_at,updated_at) VALUES 
				(".$counter.",".$c['Id_Contact'].",".$c['Id_Customer'].",NULL,NULL,".$c['Phone'].",NULL,".$c['CellPhone'].",".$c['Email'].",'2','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
					$counter++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			$query = "SELECT email
						FROM company_person
						WHERE email IS NOT NULL 
						AND email != '' 
						GROUP BY email
						HAVING count(*) > 1";

			$result = mysqli_query($this->manager->conn,$query);
			$emails = mysqli_fetch_all($result);

			foreach ($emails as $email) {
				
				$query = "SELECT person_id FROM company_person WHERE email = '".$email[0]."'";

				$result = mysqli_query($this->manager->conn,$query);
				$record = mysqli_fetch_array($result);
				$person_id = $record[0];

				$query = "SELECT * FROM company_person WHERE email = '".$email[0]."'";

				$result = mysqli_query($this->manager->conn,$query);
				$fixes = mysqli_fetch_all($result);

				foreach ($fixes as $fix) {
					$query = "UPDATE company_person SET person_id = ".$person_id." WHERE id = '".$fix[0]."'";
					if (mysqli_query($this->manager->conn,$query) === TRUE) {
						$this->updated++;
						$this->successes++;
					}
					else {
						$query = "DELETE FROM company_person WHERE id = '".$fix[0]."'";
						if (mysqli_query($this->manager->conn,$query) === TRUE) {
							$this->deleted++;
							$this->successes++;
						}
						else {
							$this->errors++;
							if ($this->debug) {
								logMessage("DEBUG: ".mysqli_error($this->manager->conn));
							}
						}
					}
				}
			}

			$query = "SELECT person_id FROM company_person 
						WHERE company_id = 1";

			$result = mysqli_query($this->manager->conn,$query);
			$ids = mysqli_fetch_all($result);

			foreach ($ids as $id) {

				$query = "DELETE FROM company_person
						  WHERE person_id = ".$id[0]." AND company_id != ".ELETTRIC80_COMPANY_ID;

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->deleted++;
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			$query = "UPDATE company_person SET group_id = 1 WHERE email = 'meli.f@elettric80.it'";

			if (mysqli_query($this->manager->conn,$query) === TRUE) {
				$this->updated++;
				$this->successes++;
			}
			else {
				$errors++;
				if ($this->debug) {
					logMessage("DEBUG: ".mysqli_error($this->manager->conn));
				}
			}

			$query = "SELECT p.id FROM people p
				  LEFT JOIN company_person cp ON (p.id = cp.person_id)
				  WHERE cp.id IS NULL";

			$result = mysqli_query($this->manager->conn,$query);
			$ids = mysqli_fetch_all($result);

			foreach ($ids as $id) {
				$query = "DELETE FROM people WHERE people.id = '".$id[0]."'";
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->deleted++;
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Deleted: ".$this->deleted);
			logMessage("Updated: ".$this->updated,'updates');
			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Equipments extends BaseClass {

	public $table_name = 'equipments';
	public $dependency_names = ['dummies','companies','equipment_types'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM CustomersEquipment');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $r) {

				$r['WarrantyExpiration'] = date('Y-m-d',strtotime($r['WarrantyExpiration']));
				
				$r = trimAndNullIfEmpty($r);
				
				$query = "INSERT INTO equipments (id,name, cc_number, serial_number, equipment_type_id, notes, warranty_expiration, company_id) 
						  VALUES (".$r['Id'].",".$r['NickName'].",".$r['CC_Number'].",".$r['Serial_Number'].",".$r['Equipment_Type'].",".$r['Notes'].",".$r['WarrantyExpiration'].",".$r['CompanyId'].")";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class CompanyMainContacts extends BaseClass {

	public $table_name = 'company_main_contact';
	public $dependency_names = ['companies','contacts'];

	public function importSelf() {

		$query = mssql_query("SELECT * FROM Customers WHERE Contact != ''");

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {
	
			$counter = 1;

			foreach ($table as $c) {

				$c = trimAndNullIfEmpty($c);

				$query_contact = mssql_query("SELECT id_Contact FROM Contact WHERE CAST(Contact.Name AS VARCHAR(50)) = ".$c['Contact']);
				$result = mssql_fetch_array($query_contact, MSSQL_ASSOC);
				$c['Main_Contact_Id'] = ($result['id_Contact'] == '' || $c['Contact'] == '') ? 'NULL' : $result['id_Contact'] + CONSTANT_GAP_CONTACTS;

				$company_person_id = findCompanyPersonId($c['Main_Contact_Id'],$this->manager->conn);
									
				if ($c['Main_Contact_Id'] != 'NULL') {
					$query = "INSERT INTO company_main_contact (id, company_id, main_contact_id) 
						VALUES (".$counter.",".$c['Id'].",".$company_person_id.")";

					if (mysqli_query($this->manager->conn, $query) === TRUE) {
						$this->successes++;
						$counter++;
					}
					else {
						$this->errors++;
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
				}
			}


			$query = "SELECT * FROM companies c
					LEFT JOIN company_main_contact cmc ON (c.id = cmc.company_id)
					WHERE cmc.company_id IS NULL";

			$result = mysqli_query($this->manager->conn,$query);
			$ids = mysqli_fetch_all($result);

			foreach ($ids as $id) {
				$query = "INSERT INTO company_main_contact (id, company_id, main_contact_id)
							SELECT ".$counter.",cp.company_id, cp.id FROM company_person cp
							WHERE cp.company_id = ".$id[0]." LIMIT 1";

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
				
				$counter++;
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class CompanyAccountManagers extends BaseClass {

	public $table_name = 'company_account_manager';
	public $dependency_names = ['companies','company_person'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Customers WHERE Id_Employee_Account_Manager IS NOT NULL');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $c) {

				$company_person_id = findCompanyPersonId($c['Id_Employee_Account_Manager'],$this->manager->conn);

				$query = "INSERT INTO company_account_manager (company_id, account_manager_id) 
						  VALUES (".$c['Id'].",".$company_person_id.")";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Tickets extends BaseClass {
		
	public $table_name = 'tickets';
	public $dependency_names = ['company_person','statuses','priorities','divisions','equipments','companies','job_types'];

	public function importSelf() {

		$query = mssql_query("SELECT * FROM Tickets WHERE Priority != ''");

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		if ($this->truncate()) {
	
			foreach ($table as $t) {

				$t['Contact_Id'] = findMatchingContactId($t);
				$t['Contact_Id'] = trim($t['Contact_Id']) == '' ? '' : trim($t['Contact_Id']) + CONSTANT_GAP_CONTACTS;
				$t['Ticket_Title'] = trim(addSlashes(str_replace('&#65533;','',strip_tags($t['Ticket_Title']))));
				$t['Ticket_Post'] = trim(addSlashes(str_replace('&#65533;','',strip_tags($t['Ticket_Post']))));
				$t['Ticket_Post'] = $t['Ticket_Post'] == '' ? $t['Ticket_Title'] : $t['Ticket_Post'];
				
				$t = trimAndNullIfEmpty($t);

				$creator_id = findCompanyPersonId($t['Creator'],$this->manager->conn);
				$assignee_id = findCompanyPersonId($t['Id_Assignee'],$this->manager->conn);
				$contact_id = findCompanyPersonId($t['Contact_Id'],$this->manager->conn);

				$query = "INSERT INTO tickets (id,title,post,post_plain_text,creator_id,assignee_id,status_id,priority_id,division_id,equipment_id,company_id,contact_id,job_type_id,created_at,updated_at) 
				 		  VALUES (".$t['Id'].",".$t['Ticket_Title'].",".$t['Ticket_Post'].",\"\",".$creator_id.",".$assignee_id.",".$t['Status'].",".$t['Priority'].",".$t['Id_System'].",".$t['Id_Equipment'].",".$t['Id_Customer'].",".$contact_id.",".$t['Job_Type'].",".$t['Date_Creation'].",".$t['Date_Update'].")";
				
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Posts extends BaseClass {

	public $table_name = 'posts';
	public $dependency_names = ['tickets','company_person'];

	public function importSelf() {

		$query = mssql_query("SELECT p.*, d.Counter FROM Posts p
							LEFT JOIN (SELECT Second_Id, count(*) Counter FROM Documents WHERE Type = 'post' GROUP BY Second_Id) d ON d.Second_Id = p.Id 
							WHERE (p.Post IS NOT NULL AND p.Post NOT LIKE '')
							OR d.Counter > 0");

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {
	
			foreach ($table as $p) {

				$p['Creation_Date'] = $p['Date_Creation']." ".$p['Time'];
				$p['Creation_Date'] = str_replace(".0000000","", $p['Creation_Date']);
				$p['Post'] = trim(addSlashes(str_replace('&#65533;','',strip_tags($p['Post']))));
				$p['Post_Public'] = $p['Post_Public'] == '' ? '0' : '1';

				if ($p['Id_Customer_User'] != '') {
					$subquery1 = mssql_query("SELECT * FROM Customer_User_Login WHERE Customer_Id = '".$p['Id_Customer_User']."'");
					$result1 = mssql_fetch_array($subquery1, MSSQL_ASSOC);
					$subquery2 = mssql_query("SELECT * FROM Tickets WHERE Id = '".$p['Id_Ticket']."'");
					$result2 = mssql_fetch_array($subquery2, MSSQL_ASSOC);
					$subquery3 = "SELECT * FROM company_person WHERE email = '".trim($result1['email_customer_user'])."' AND company_id = '".$result2['Id_Customer']."'";
					$result = mysqli_query($this->manager->conn,$subquery3);
					$record = mysqli_fetch_array($result);
					$author_id = $record['id'];
				}

				$p = trimAndNullIfEmpty($p);

				$p['Post'] = $p['Post'] == 'NULL' ? $p['Counter'] > 1 ? '"see attachments"' : '"see attachment"' : $p['Post'];

				if (!isset($author_id)) 
					$author_id = findCompanyPersonId($p['Author'],$this->manager->conn);

				$query = "INSERT INTO posts (id,ticket_id,post,post_plain_text,author_id,is_public,created_at,updated_at) 
						  VALUES (".$p['Id'].",".$p['Id_Ticket'].",".$p['Post'].",\"\",".$author_id.",".$p['Post_Public'].",".$p['Creation_Date'].",".$p['Creation_Date'].")";
				

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}

				$author_id = null;
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class TicketsHistroy extends BaseClass {

	public $table_name = 'tickets_history';
	public $dependency_names = ['tickets'];

	public function importSelf() {

		$query = mssql_query("SELECT th.*, CONVERT(VARCHAR(19), th.Date_Time, 120) as 'date_time_formatted', e.Email as 'email_changed_by', e2.Email as 'email_assignee' 
							  FROM Ticket_History th
							  LEFT JOIN Employees e ON (e.id = th.Id_User_changed_by)
							  LEFT JOIN Employees e2 ON (e2.id = th.Id_User)");

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {

			$counter = 1;
	
			foreach ($table as $t) {

				$query = "SELECT * FROM tickets WHERE id = '".trim($t['Id_Ticket'])."'";
				$result = mysqli_query($this->manager->conn,$query);
				$ti = mysqli_fetch_assoc($result);
				if (count($ti)) $ti = trimAndNullIfEmpty($ti);

				if (count($ti)) {

					$query = "SELECT * FROM company_person WHERE email = '".trim($t['email_changed_by'])."'";
					$result = mysqli_query($this->manager->conn,$query);
					$changer = mysqli_fetch_assoc($result);
					if (count($changer)) $changer = trimAndNullIfEmpty($changer);
					$changer_id = isset($changer) ? $changer['id'] : 'NULL';

					$query = "SELECT * FROM company_person WHERE email = '".trim($t['email_assignee'])."'";
					$result = mysqli_query($this->manager->conn,$query);
					$assignee = mysqli_fetch_assoc($result);
					if (count($assignee)) $assignee = trimAndNullIfEmpty($assignee);
					$assignee_id = isset($assignee) ? $assignee['id'] : $ti['assignee_id'];

					$t = trimAndNullIfEmpty($t);

					$query = "INSERT INTO tickets_history (id,ticket_id,changer_id,title,post,post_plain_text,creator_id,assignee_id,status_id,priority_id,division_id,equipment_id,company_id,contact_id,job_type_id,created_at,updated_at) 
					 		  VALUES (".$counter.",".$t['Id_Ticket'].",".$changer_id.",".$ti['title'].",".$ti['post'].",\"\",".$ti['creator_id'].",".$assignee_id.",".$t['Id_Status'].",".$t['Id_Priority'].",".$t['Id_Division'].",".$ti['equipment_id'].",".$ti['company_id'].",".$ti['contact_id'].",".$ti['job_type_id'].",".$t['date_time_formatted'].",".$t['date_time_formatted'].")";
					 		  
					if (mysqli_query($this->manager->conn,$query) === TRUE) {
						$counter++;
						$this->successes++;
					}
					else {
						$this->errors++;
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class TagTickets extends BaseClass {

	public $table_name = 'tag_ticket';
	public $dependency_names = ['tags','tickets'];

	public function importSelf() {

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
								(Orders FOR Tag IN (Tag1, Tag2, Tag3)) AS unpvt');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {

			$counter = 0;
	
			foreach ($table as $t) {
				
				$query = "SELECT * FROM tags WHERE name = '".$t['Orders']."'";

				$result = mysqli_query($this->manager->conn,$query);
				$record = mysqli_fetch_array($result);
				$tag_id = $record[0];

				$t = trimAndNullIfEmpty($t);

				$query = "INSERT INTO tag_ticket (id,ticket_id,tag_id) 
						  VALUES (".$counter.",".$t['Id'].",".$tag_id.")";

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
					$counter++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		}

		logMessage("Successes: ".$this->successes,'successes');
		logMessage("Errors: ".$this->errors,'errors');
	}
}

class Services extends BaseClass {

	public $table_name = 'services';
	public $dependency_names = ['companies','company_person'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Service_Request');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {
	
			foreach ($table as $s) {

				$s['Id_hotel'] = $s['Id_hotel'] == "0" ? "" : $s['Id_hotel'];

				$s['Id_contact'] = $s['Id_contact'] == '' ? '' : $s['Id_contact'] + CONSTANT_GAP_CONTACTS;

				$s = trimAndNullIfEmpty($s);

				$internal_contact_id = findCompanyPersonId($s['assigment_contact'],$this->manager->conn);
				$external_contact_id = findCompanyPersonId($s['Id_contact'],$this->manager->conn);

				$query = "INSERT INTO services (id, company_id,internal_contact_id,external_contact_id,job_number_internal,job_number_onsite,job_number_remote,created_at,updated_at) 
						  VALUES (".$s['Id'].",".$s['Id_company'].",".$internal_contact_id.",".$external_contact_id.",".$s['assigment_internal'].",".$s['assigment_onsite'].",".$s['remote_install_job_number'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
				
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		}

		logMessage("Successes: ".$this->successes,'successes');
		logMessage("Errors: ".$this->errors,'errors');
	}
}

class ServiceTechnicians extends BaseClass {

	public $table_name = 'service_technician';
	public $dependency_names = ['services','company_person','divisions'];

	public function importSelf() {

		$query = mssql_query('SELECT * FROM Service_Request_Technicians WHERE Id_service_request IN (SELECT DISTINCT Id FROM Service_Request)');

		while ($row = mssql_fetch_array($query, MSSQL_ASSOC)) $table[] = $row;

		mssql_free_result($query);

		if ($this->truncate()) {

			$counter = 1;
	
			foreach ($table as $s) {

				$s['work_description'] = str_replace("\"","'",$s['work_description']);
				$total_days = strtotime($s['onsite_completion']) - strtotime($s['onsite_start']);
				$s['hours_estimated_onsite'] = strpos(strtolower($s['hours_estimated_onsite']), "day") === false ? $s['hours_estimated_onsite'] : str_replace("/Day","",$s['hours_estimated_onsite']) * $total_days;
				$s['Id_employee'] = findCompanyPersonId($s['Id_employee'],$this->manager->conn);

				$s = trimAndNullIfEmpty($s);

				$query = 	"INSERT INTO service_technician (id,service_id, technician_id, division_id, work_description, internal_start, internal_end, internal_estimated_hours, onsite_start, onsite_end, onsite_estimated_hours, remote_start, remote_end, remote_estimated_hours, created_at, updated_at)
							VALUES (".$counter.",".$s['Id_service_request'].",".$s['Id_employee'].",".$s['Id_service_role'].",".$s['work_description'].",".$s['internal_start'].",".$s['internal_completion'].",".$s['hours_estimated_internal'].",".$s['onsite_start'].",".$s['onsite_completion'].",".$s['hours_estimated_onsite'].",NULL,NULL,NULL,'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
					$counter++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		}

		logMessage("Successes: ".$this->successes,'successes');
		logMessage("Errors: ".$this->errors,'errors');
	}
}

class Users extends BaseClass {

	public $table_name = 'users';
	public $dependency_names = ['people','company_person'];
	private $updated = 0;

	public function importSelf() {
	
		$query_company_users = mssql_query("SELECT C.Id_Contact, LOWER(LTRIM(RTRIM(CAST(CU.Customer_User AS VARCHAR(100))))) AS Customer_User, CU.Customer_Password
						  		FROM Contact AS C
						  		INNER JOIN Customer_User_Login AS CU ON 
						  		((RTRIM(LTRIM(CAST(C.Name AS VARCHAR(100)))) = RTRIM(LTRIM(CU.Customer_Name))+' '+RTRIM(LTRIM(CU.Customer_Last_Name))
						  		OR RTRIM(LTRIM(CAST(C.Name AS VARCHAR(100)))) = RTRIM(LTRIM(CU.Customer_Last_Name))+' '+RTRIM(LTRIM(CU.Customer_Name))
						  		OR RTRIM(LTRIM(CAST(C.Email AS VARCHAR(100)))) = RTRIM(LTRIM(CAST(CU.email_customer_user AS VARCHAR(100)))))
						  		AND C.Id_Customer = CU.Company_Id)
						  		ORDER BY Id_Contact");

		while ($row = mssql_fetch_array($query_company_users, MSSQL_ASSOC)) $table[] = $row;

		mssql_free_result($query_company_users);

		if ($this->truncate()) {
	
			$counter = 1;

			foreach ($table as $u) {

				$u['Id_Contact'] = $u['Id_Contact'] == '' ? '' : $u['Id_Contact'] + CONSTANT_GAP_CONTACTS;

				$u = trimAndNullIfEmpty($u);

				$query = "INSERT INTO users (id,person_id,username,password,created_at,updated_at) 
						  VALUES (".$counter.",".$u['Id_Contact'].",".$u['Customer_User'].",".$u['Customer_Password'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			$users = [];

			$query_employee_logins = mssql_query("SELECT * FROM Login");

			while ($row = mssql_fetch_array($query_employee_logins, MSSQL_ASSOC)) $users[] = $row;

			mssql_free_result($query_employee_logins);

			foreach ($users as $u) {

				$u = trimAndNullIfEmpty($u);

				$query = "INSERT INTO users (person_id,username,password,created_at,updated_at) 
						  VALUES (".$u['Employee_Id'].",".$u['User_name'].",".$u['User_password'].",'".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$this->successes++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}

			// set active contacts for users

			$users = [];
			$people = [];

			$query = "SELECT id FROM users";
			$result = mysqli_query($this->manager->conn,$query);
			while ($row = mysqli_fetch_array($result)) $users[] = $row;

			foreach ($users as $user) {
				
				$query = "SELECT cp.id
						  FROM users u 
						  INNER JOIN people p ON p.id = u.person_id 
						  INNER JOIN company_person cp ON cp.person_id = p.id
						  WHERE u.id = ".$user['id']." LIMIT 1";

				$result = mysqli_query($this->manager->conn,$query);
				$company_person = mysqli_fetch_array($result);
				
				if ($company_person) {
					$query = "UPDATE users SET active_contact_id = ".$company_person['id']." WHERE id = '".$user['id']."'";
					if (mysqli_query($this->manager->conn,$query) === TRUE) {
						$this->updated++;
						$this->successes++;
					}
					else {
						$this->errors++;
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
				}
			}
		}

		logMessage("Updated: ".$this->updated,'updates');
		logMessage("Successes: ".$this->successes,'successes');
		logMessage("Errors: ".$this->errors,'errors');
	}
}

class Hotels extends BaseClass {

	public $table_name = 'hotels';
	public $dependency_names = ['companies'];

	public function importSelf() {

		$query = "SELECT * FROM companies WHERE address IS NOT NULL";
				
		$result = mysqli_query($this->manager->conn, $query);
		$companies = mysqli_fetch_all($result,MYSQLI_BOTH);

		if ($this->truncate()) {

			$counter = 1;

			foreach ($companies as $company) {

				$address = $company['address']." ".$company['city']." ".$company['zip_code']." ".$company['state']." ".$company['country'];

				$address = str_replace(" ", "%20", $address);
				$address = str_replace("'", "%27", $address);
				$details_url = "https://maps.googleapis.com/maps/api/geocode/json?key=".GOOGLE_API_KEY."&address=".$address;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $details_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$geoloc = json_decode(curl_exec($ch), true);

				if (count($geoloc['results']) == 1) {

					$lat = $geoloc['results'][0]['geometry']['location']['lat'];
					$lng = $geoloc['results'][0]['geometry']['location']['lng'];

					$details_url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=".GOOGLE_API_KEY."&location=".$lat.",".$lng."&radius=10000&types=lodging";

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $details_url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response = json_decode(curl_exec($ch), true);

					foreach ($response['results'] as $element) {

						$dest_lat = $element['geometry']['location']['lat'];
						$dest_lng = $element['geometry']['location']['lng'];

						$matrix_url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$lat.",".$lng."&destinations=".$dest_lat.",".$dest_lng."&mode=walking";

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $matrix_url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$response = json_decode(curl_exec($ch), true);

						if (count($response['rows']) > 0) {

							$matrix['distance'] = $response['rows'][0]['elements'][0]['distance']['value'];
							$matrix['walking_time'] = $response['rows'][0]['elements'][0]['duration']['value'];

							$matrix_url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$lat.",".$lng."&destinations=".$dest_lat.",".$dest_lng."&mode=driving";

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $matrix_url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$response = json_decode(curl_exec($ch), true);

							if (count($response['rows']) > 0) {

								$matrix['driving_time'] = $response['rows'][0]['elements'][0]['duration']['value'];

								$element['rating'] = isset($element['rating']) ? $element['rating'] : 'NULL';

								$query = "INSERT INTO hotels (id,name,address,company_id,rating,walking_time,driving_time,distance) VALUES (".$counter.",\"".str_replace('"',"'",$element['name'])."\",\"".str_replace('"',"'",$element['vicinity'])."\",".$company['id'].",".$element['rating'].",".$matrix['walking_time'].",".$matrix['driving_time'].",".$matrix['distance'].")";

								if (mysqli_query($this->manager->conn,$query) === TRUE) {
									$this->successes++;
									$counter++;
								}
								else {
									$this->errors++;
									if ($this->debug) {
										logMessage("DEBUG: ".mysqli_error($this->manager->conn));
									}
								}
							}
						}
					}
				}
			}

			logMessage("Successes: ".$this->successes,'successes');
			logMessage("Errors: ".$this->errors,'errors');
		}
	}
}

class Dummies extends BaseClass {

	public $table_name = 'dummies'; // this is not the real name of the table
	public $dependency_names = [];

	public function importSelf() {

		$queries = [

			"SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO'",

			"SET foreign_key_checks = 0",

			"DELETE FROM job_types WHERE id = 0",
			"DELETE FROM priorities WHERE id = 0",
			"DELETE FROM statuses WHERE id = 0",
			"DELETE FROM equipments WHERE id = 0",
			"DELETE FROM company_person WHERE id = 0",
			"DELETE FROM group_types WHERE id = 0",
			"DELETE FROM titles WHERE id = 0",
			"DELETE FROM departments WHERE id = 0",
			"DELETE FROM companies WHERE id = 0",
			"DELETE FROM equipment_types WHERE id = 0",
			"DELETE FROM divisions WHERE id = 0",
			"DELETE FROM support_types WHERE id = 0",
			"DELETE FROM connection_types WHERE id = 0",
			"DELETE FROM people WHERE id = 0",

			"SET foreign_key_checks = 1",

			"INSERT INTO people (id,first_name,last_name) VALUES ('0','[undefined]','[undefined]')",
			"INSERT INTO connection_types (id,name,description) VALUES (0,'[undefined]','[undefined]')",
			"INSERT INTO support_types (id,name) VALUES (0,'[undefined]')",
			"INSERT INTO divisions (id,name) VALUES (0,'[undefined]')",
			"INSERT INTO equipment_types (id,name) VALUES (0,'[undefined]')",
			"INSERT INTO companies (id, name, address, country, city, state, zip_code, connection_type_id, support_type_id) VALUES (0,'[undefined]','[undefined]','[undefined]','[undefined]','[undefined]','[undefined]',0,0)",
			"INSERT INTO departments (id,name) VALUES (0,'[undefined]')",
			"INSERT INTO titles (id,name) VALUES (0,'[undefined]')",
			"INSERT INTO group_types (id, name, display_name, description) VALUES (0,'[undefined]','[undefined]','[undefined]')",
			"INSERT INTO company_person (id, person_id, company_id, department_id, title_id,phone,extension,cellphone,email,group_type_id) VALUES (0,0,0,0,0,'[undefined]','[undefined]','[undefined]',0,'[undefined]')",
			"INSERT INTO equipments (id,name, cc_number, serial_number, equipment_type_id, notes, warranty_expiration, company_id) VALUES (0,'[undefined]','[undefined]','[undefined]',0,'[undefined]','[undefined]',0)",
			"INSERT INTO statuses (id,name) VALUES (0,'[undefined]')",
			"INSERT INTO priorities (id,name) VALUES (0,'[undefined]')",
			"INSERT INTO job_types (id,name) VALUES (0,'[undefined]')"
		];
				
		foreach ($queries as $query) {							
			
			if (mysqli_query($this->manager->conn,$query) === TRUE) {
				$this->successes++;
			}
			else {
				$this->errors++;
				if ($this->debug) {
					logMessage("DEBUG: ".mysqli_error($this->manager->conn));
				}
			}
		}

		logMessage("Successes: ".$this->successes,'successes');
		logMessage("Errors: ".$this->errors,'errors');
	}
}

class Attachments extends BaseClass {

	public $table_name = 'files';
	public $dependency_names = ['posts','tickets'];

	public function importSelf() {

		$deleted_db = $deleted_fs = $added_db = $added_fs = 0;

		// remove records on db and file on filesystem
		$query = "SELECT * FROM files WHERE resource_type LIKE '%Post%'";
		$result = mysqli_query($this->manager->conn, $query);
		$records = mysqli_fetch_all($result,MYSQL_ASSOC);

		foreach ($records as $record) {

			$delete_db_record = false;

			$file_name = PUBLIC_FOLDER.DS.$record['file_path'].DS.$record['file_name'];
			
			if (file_exists($file_name)) {
				if (unlink($file_name)) {
					$delete_db_record = true;
					$deleted_fs++;
				} else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: Unable to delete ".$file_path);
					}
				}
			}
			else $delete_db_record = true;

			if ($delete_db_record == true) {
				$query = "DELETE FROM files WHERE id = ".$record['id'];
					
				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$deleted_db++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		}

		$query = mssql_query(	"SELECT d.Id, d.Second_Id, d.Path, p.Author, c.counter, p.Date_Creation, p.Time
								FROM Documents d
								INNER JOIN Posts p ON p.Id = d.Second_Id
								LEFT JOIN (
									SELECT Path, Count(*) as counter
									FROM Documents
									GROUP BY Path
								) as c ON c.Path = d.Path 
								WHERE c.path IS NOT NULL 
								AND c.path != '' 
								AND Second_Id IS NOT NULL 
								AND Type = 'post'
								ORDER BY Id DESC");

		$result = array();

		while ($row = mssql_fetch_assoc($query)) $result[] = $row;

		foreach ($result as $m) {

			$query = "SELECT COUNT(*) FROM posts WHERE id = ".$m['Second_Id'];
			$result = mysqli_query($this->manager->conn,$query);
			$post = mysqli_fetch_array($result);

			if ($post[0] > 0) {

				$url = 'http://www.elettric80inc.com/convergence/uploads/posts_documents/'.rawurlencode($m['Path']);
				$content = @file_get_contents($url);

				if ($content) {
					// insert record in the db8
					$m['Date_Creation'] = $m['Date_Creation']." ".$m['Time'];
					$m['Date_Creation'] = str_replace(".0000000","", $m['Date_Creation']);
					$uploader_id = findCompanyPersonId($m['Author'],$this->manager->conn);
					$temp = explode(".",$m['Path']);
					$extension = $temp[count($temp)-1];
					$file_name = 'POST#'.$m['Second_Id']."UPLOADER#".$uploader_id."UUID#".uniqid().".".$extension;
					$query = "INSERT INTO files (id,name,file_path,file_name,file_extension,resource_type,resource_id,uploader_id, thumbnail_id, created_at, updated_at) VALUES ('".$m['Id']."','".$m['Path']."','attachments','".$file_name."','".$extension."','App\\\Models\\\Post','".$m['Second_Id']."','".$uploader_id."',NULL,'".$m['Date_Creation']."','".$m['Date_Creation']."')";

					if (mysqli_query($this->manager->conn,$query) === TRUE) {
						$added_db++;
						if (file_put_contents(ATTACHMENTS.DS.$file_name, $content)) {
							$added_fs++;
						}
						else {
							$this->errors++;
							if ($this->debug) {
								logMessage("DEBUG: The file couldn't be copied @ ".ATTACHMENTS.DS.$file_name);
							}
						}
					}
					else {
						$this->errors++;
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
				}
			}
		}

		logMessage("Removed frm FileSystem: ".$deleted_fs,'successes');
		logMessage("Removed frm Database: ".$deleted_db,'successes');
		logMessage("Added to FileSystem: ".$added_fs,'successes');
		logMessage("Added to Database: ".$added_db,'successes');
	}
}

class Thumbnails extends BaseClass {

	public $table_name = 'thumbnails';  // this is not the real name of the table
	public $dependency_names = [];
	private $updated = 0;

	public function importSelf() {

		$deleted_db = $deleted_fs = $added_db = $added_fs = 0;

		// remove records on db and file on filesystem
		$query = "SELECT * FROM files WHERE resource_type LIKE '%Thumbnail%'";
		$result = mysqli_query($this->manager->conn, $query);
		$records = mysqli_fetch_all($result,MYSQL_ASSOC);

		foreach ($records as $record) {

			$delete_db_record = false;

			$file_name = PUBLIC_FOLDER.DS.$record['file_path'].DS.$record['file_name'];
			
			if (file_exists($file_name)) {
				if (unlink($file_name)) {
					$delete_db_record = true;
					$deleted_fs++;
				} else {
					$this->errors++;
				}
			}
			else $delete_db_record = true;

			if ($delete_db_record == true) {

				// remove thumbnail reference to resource
				$query = "UPDATE files SET thumbnail_id = NULL WHERE thumbnail_id = ".$record['id'];
				mysqli_query($this->manager->conn,$query);

				// remove db record 
				$query = "DELETE FROM files WHERE id = ".$record['id'];

				if (mysqli_query($this->manager->conn,$query) === TRUE) {
					$deleted_db++;
				}
				else {
					$this->errors++;
					if ($this->debug) {
						logMessage("DEBUG: ".mysqli_error($this->manager->conn));
					}
				}
			}
		}

		$query = "SELECT * FROM files WHERE thumbnail_id IS NULL AND resource_type NOT LIKE '%Thumbnail%'";
		$result = mysqli_query($this->manager->conn, $query);
		$images = mysqli_fetch_all($result,MYSQLI_BOTH);

		foreach ($images as $image) {

			$remove_from_temp = false;

			$path_info = pathinfo($image['file_name']);

			if (!in_array($path_info['extension'],['zip','7z','rar','pam','tgz','bz2','iso','ace'])) 
			{
				$path = $image['file_path'].DS.$image['file_name'];
			
				if (in_array($path_info['extension'],['xlsx','xls','docx','doc','odt','ppt','pptx','pps','ppsx','txt','csv','log'])) 
				{
					$command = "sudo ".env('LIBREOFFICE','soffice')." --headless --convert-to pdf:writer_pdf_Export --outdir ".base_path().DS."public".DS."tmp ".base_path().DS."public".DS.$path." > /dev/null";
					exec($command);
					$source = base_path().DS."public".DS."tmp".DS.$path_info['filename'].".pdf[0]";
					$remove_from_temp = base_path().DS."public".DS."tmp".DS.$path_info['filename'].".pdf";
				} 
				elseif (in_array($path_info['extension'],['mp4','mpg','avi','mkv','flv','xvid','divx','mpeg','mov','vid','vob'])) {
					$command = "sudo ".env('FFMPEG','ffmpeg')." -i ".base_path().DS."public".DS.$path." -ss 00:00:01.000 -vframes 1 ".base_path().DS."public".DS."tmp".DS.$path_info['filename'].".png > /dev/null";
					exec($command);
					$source = base_path().DS."public".DS."tmp".DS.$path_info['filename'].".png";
					$remove_from_temp = base_path().DS."public".DS."tmp".DS.$path_info['filename'].".png";
				} 
				else {
					$image['file_name'] .= $path_info["extension"] == "pdf" ? "[0]" : ""; 
					$source = base_path().DS."public".DS.$image['file_path'].DS.$image['file_name'];						
				}

				$destination = THUMBNAILS.DS.$path_info['filename'].".png";
				$command2 = "sudo ".env('CONVERT','convert')." -resize '384x384' $source $destination";
				
				$result = exec($command2);

				if (file_exists($destination)) {

					if ($remove_from_temp) unlink($remove_from_temp);

					$added_fs++;

					$query = "INSERT INTO files (name,file_path,file_name,file_extension,resource_type,resource_id,uploader_id, thumbnail_id, created_at, updated_at) VALUES ('".$path_info['filename'].".png','thumbnails','".$path_info['filename'].".png','png','Thumbnail',NULL,'".$image['uploader_id']."',NULL,'".$image['created_at']."','".$image['created_at']."')";

					if (mysqli_query($this->manager->conn,$query) === TRUE) {
						$id = mysqli_insert_id($this->manager->conn);
						$query = "UPDATE files SET thumbnail_id = ".$id." WHERE id = ".$image['id'];

						if (mysqli_query($this->manager->conn,$query) === TRUE) {
							$added_db++;
						}
						else {
							if ($this->debug) {
								logMessage("DEBUG: ".mysqli_error($this->manager->conn));
							}
						}
					}
					else {
						if ($this->debug) {
							logMessage("DEBUG: ".mysqli_error($this->manager->conn));
						}
					}
				}
				else {
					if ($this->debug) {
						logMessage("DEBUG: The file couldn't be copied @ ".$destination);
					}
				}
			}
		}

		logMessage("Removed frm FileSystem: ".$deleted_fs,'successes');
		logMessage("Removed frm Database: ".$deleted_db,'successes');
		logMessage("Added to FileSystem: ".$added_fs,'successes');
		logMessage("Added to Database: ".$added_db,'successes');
	}
}