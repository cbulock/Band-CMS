<?php

	include_once('functions.php');
  
  define('DB_HOSTNAME', 'localhost');
	define('DB_DATABASE', '');
	define('DB_USERNAME', '');
	define('DB_PASSWORD', '');
	define('DB_PREFIX', ''); //prefix to tack on to all tables in the database (leave blank for none)
  
	$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die('Database connection failed.');
	$connect = mysql_select_db(DB_DATABASE) or die('The database does not exist.');
    
	$configArray = db_get_table('config');
  foreach($configArray as $item)
  {
		switch ($item['type'])
    {
    	case 'string':
  			define($item['name'], $item['value']);
        break;
      case 'integer':
      	define($item['name'], intval($item['value']));
      	break;
      case 'float':
      	define($item['name'], floatval($item['value']));
      	break;
    }
    
  }
  
?>
