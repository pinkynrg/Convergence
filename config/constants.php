<?php 

	define("CONVERGENCE_HOST", "198.154.99.22:1088");
	define("CONVERGENCE_DB", "Elettric80Inc");	
	define("CONVERGENCE_USER", "saa");
	define("CONVERGENCE_PASS", "V09Wd519");
	define("LOCAL_HOST", env('DB_HOST', 'localhost'));	
	define("LOCAL_DB", env('DB_DATABASE', 'forge'));	
	define("LOCAL_USER", env('DB_USERNAME', 'forge'));
	define("LOCAL_PASS", env('DB_PASSWORD', ''));
	define("CONSTANT_GAP_CONTACTS",500);
	define("ELETTRIC80_COMPANY_ID",1);
	define("GOOGLE_API_KEY","AIzaSyDrtPZysOJe6_m4wkJ7x384CnTqJ-7ROY4");
	define("LOCATION_THIS","app/Libraries/ImportManager.php");
	define("DS",DIRECTORY_SEPARATOR);
	define("PUBLIC_FOLDER",base_path().DS."public");
	define("ATTACHMENTS",PUBLIC_FOLDER.DS."attachments");
	define("THUMBNAILS",PUBLIC_FOLDER.DS."thumbnails");
	define("IMAGES",PUBLIC_FOLDER.DS."images");	
	define("STYLE_IMAGES",IMAGES.DS."style");	
	define("TEMP",PUBLIC_FOLDER.DS."tmp");
	define("RESET_COLOR","\e[0m");
	define("SET_RED","\e[0;31m");
	define("SET_GREEN","\e[0;32m");
	define("SET_YELLOW","\e[0;33m");
	define("SET_PURPLE","\e[0;35m");
	define("TICKET_DRAFT_STATUS_ID",9);
	define("POST_DRAFT_STATUS_ID",1);
	define("DOMAIN",isset($_SERVER['SERVER_NAME']) ? strpos($_SERVER['SERVER_NAME'],"local") !== FALSE ? "convergence.provvedo.com" : $_SERVER['SERVER_NAME'] : '' );
	define("PROTOCOL",isset($_SERVER['SERVER_PORT']) ? (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://" : '' );
	define("SITE_URL",PROTOCOL.DOMAIN)

?>
