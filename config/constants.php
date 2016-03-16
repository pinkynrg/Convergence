<?php 

// GENERAL CONFIGURATION
define("DOMAIN", isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'],"local") !== FALSE ? $_SERVER['HTTP_HOST'] : 'local.convergence.it');
define("PROTOCOL","http://");
define("SITE_URL",PROTOCOL.DOMAIN);
define("PAGINATION",50);
define("MAX_PAGINATION",200);

// IMPORT CONFIGURATION FROM Convergence 1.0
define("CONVERGENCE_HOST", "198.154.99.22:1088");
define("CONVERGENCE_DB", "Elettric80Inc");	
define("CONVERGENCE_USER", "saa");
define("CONVERGENCE_PASS", "V09Wd519");
define("LOCAL_HOST", env('DB_HOST', 'localhost'));	
define("LOCAL_DB", env('DB_DATABASE', 'forge'));	
define("LOCAL_USER", env('DB_USERNAME', 'forge'));
define("LOCAL_PASS", env('DB_PASSWORD', ''));
define("CONSTANT_GAP_CONTACTS",500);
define("GOOGLE_API_KEY","AIzaSyDrtPZysOJe6_m4wkJ7x384CnTqJ-7ROY4");
define("DS",DIRECTORY_SEPARATOR);
define("IMPORTER_LOCATION",base_path().DS."app/Libraries/ImportManager.php");
define("STORAGE_FOLDER",base_path().DS."storage");
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

// RELEVANT IDs 
define("DEFAULT_PROFILE_PICTURE_ID",10000);
define("DEFAULT_MISSING_PICTURE_ID",20000);
define("ADMIN_PERSON_ID",173);
define("ELETTRIC80_COMPANY_ID",1);
define("TEST_COMPANY_ID",178);

define("EVENT_ASSIGNEE_ID",1);
define("EVENT_HELPDESK_MANAGER_ID",2);
define("EVENT_ACCOUNT_MANAGER_ID",3);
define("EVENT_TEAM_LEADER_ID",4);
define("EVENT_FIELD_MANAGER_ID",5);
define("EVENT_TECHNICAL_MANAGER_ID",6);
define("EVENT_SALES_AREA_MANAGER_ID",7);
define("EVENT_CUSTOMER_SERVICE_MANAGER_ID",8);
define("EVENT_THE_PRESIDENT_ID",9);

define("LGV_DIVISION_ID",1);
define("PLC_DIVISION_ID",2);
define("PC_DIVISION_ID",3);

define("EMPLOYEE_GROUP_TYPE_ID",1);
define("CUSTOMER_GROUP_TYPE_ID",2);
define("HOST_EMPLOYEE_GROUP_ID",3);
define("HOST_CUSTOMER_GROUP_ID",11);
define("TICKET_UNDEFINED_STATUS_ID",0);
define("TICKET_NEW_STATUS_ID",1);
define("TICKET_IN_PROGRESS_STATUS_ID",2);
define("TICKET_WFF_STATUS_ID",3);
define("TICKET_WFP_STATUS_ID",4);
define("TICKET_REQUESTING_STATUS_ID",5);
define("TICKET_SOLVED_STATUS_ID",6);
define("TICKET_CLOSED_STATUS_ID",7);
define("TICKET_DRAFT_STATUS_ID",8);
define("TICKETS_ACTIVE_STATUS_IDS",TICKET_REQUESTING_STATUS_ID.":".TICKET_NEW_STATUS_ID.":".TICKET_IN_PROGRESS_STATUS_ID.":".TICKET_WFP_STATUS_ID);
define("POST_DRAFT_STATUS_ID",1);
define("POST_PRIVATE_STATUS_ID",2);
define("POST_PUBLIC_STATUS_ID",3);
define("DEFAULT_ESCALATION_PROFILE_ID",1);

// ICONS
define("MISSING_ICON", "fa fa-question-circle");
define("TICKETS_ICON", "fa fa-ticket");
define("MANAGE_ICON", "fa fa-cog");
define("COMPANIES_ICON", "fa fa-building");
define("CONTACTS_ICON", "fa fa-book");
define("USERS_ICON", "fa fa-book");
define("EQUIPMENT_ICON", "fa fa-wrench");
define("SERVICES_ICON", "fa fa-server");
define("ESCALATIONS_ICON", "fa fa-bolt");
define("ACCESS_ICON", "fa fa-hand-paper-o");
define("PERMISSIONS_ICON", "fa fa-unlock");
define("ROLES_ICON", "fa fa-male");
define("GROUPS_ICON", "fa fa-users");
define("GROUP_TYPES_ICON", "fa fa-bars");
define("INFO_ICON", "fa fa-info");
define("DASHBOARD_ICON", "fa fa-dashboard");
define("HOTELS_ICON", "fa fa-building");
define("STATISTICS_ICON", "fa fa-line-chart");
define("TICKET_NEW_ICON", "fa fa-exclamation-circle");
define("TICKET_IN_PROGRESS_ICON", "fa fa-coffee");
define("TICKET_WFF_ICON", "fa fa-commenting-o");
define("TICKET_WFP_ICON", "fa fa-truck");
define("TICKET_REQUESTING_ICON", "fa fa-male");
define("TICKET_DRAFT_ICON", "fa fa-file-text-o");
define("TICKET_SOLVED_ICON", "fa fa-check-circle-o");
define("TICKET_CLOSED_ICON", "fa fa-times-circle-o");

?>
