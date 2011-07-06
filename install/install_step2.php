<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

  <title>CMS Installer - Step 2</title>
<style>
body {
font-family:Helvetica,Arial,sans-serif;
}
</style>
</head>
<body>

<h1>CMS Installer</h1>


<h3>Step
2 - Creating inital configuration and database</h3>

<?php

//Test submitted db info

if ($link = @mysql_connect($_POST['DB_HOSTNAME'], $_POST['DB_USERNAME'], $_POST['DB_PASSWORD'])) {
  echo "<p>MySQL connection fine.</p>\n";
} else {
  echo "<p>Could not connect to MySQL.  Click back in your browser and check the database settings.</p>";
	echo "<p>The following error was returned: ". mysql_error() ."</p>\n";
  exit;
}

if ($link = @mysql_select_db($_POST['DB_DATABASE'], $link)) {
  echo "<p>Database connection fine.</p>\n";
} else {
  echo "<p>Could not connect to database.  Click back in your browser and check the database settings.</p>";
	echo "<p>The following error was returned: ". mysql_error() ."</p>\n";
  exit;
}

//Verify admin pw is valid
if ($_POST['HostPW'] <> $_POST['HostPW2']) {
	 echo "<p>Admin accounts passwords do not match, please go back and reenter passwords.</p>";
	 exit;
}

//config file to be created

$configfile = "<?php\n";
$configfile .= "\n";
$configfile .= "	include_once('functions.php');\n";
$configfile .= "  \n";
$configfile .= "  define('DB_HOSTNAME', '".$_POST['DB_HOSTNAME']."');\n";
$configfile .= "	define('DB_DATABASE', '".$_POST['DB_DATABASE']."');\n";
$configfile .= "	define('DB_USERNAME', '".$_POST['DB_USERNAME']."');\n";
$configfile .= "	define('DB_PASSWORD', '".$_POST['DB_PASSWORD']."');\n";
$configfile .= "	define('DB_PREFIX', '".$_POST['DB_PREFIX']."'); //prefix to tack on to all tables in the database (leave blank for none)\n";
$configfile .= "  \n";
$configfile .= "	\$link = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die('Database connection failed.');\n";
$configfile .= "	\$connect = mysql_select_db(DB_DATABASE) or die('The database does not exist.');\n";
$configfile .= "    \n";
$configfile .= "	\$configArray = db_get_table('config');\n";
$configfile .= "  foreach(\$configArray as \$item)\n";
$configfile .= "  {\n";
$configfile .= "		switch (\$item['type'])\n";
$configfile .= "    {\n";
$configfile .= "    	case 'string':\n";
$configfile .= "  			define(\$item['name'], \$item['value']);\n";
$configfile .= "        break;\n";
$configfile .= "      case 'integer':\n";
$configfile .= "      	define(\$item['name'], intval(\$item['value']));\n";
$configfile .= "      	break;\n";
$configfile .= "      case 'float':\n";
$configfile .= "      	define(\$item['name'], floatval(\$item['value']));\n";
$configfile .= "      	break;\n";
$configfile .= "    }\n";
$configfile .= "    \n";
$configfile .= "  }\n";
$configfile .= "  \n";
$configfile .= "?>";

//write the config file
if (!$file = fopen("../includes/config.php", 'w'))
{
 echo "Could not create config file.  Check file permissions and make sure the includes directory is writable.";
 exit;
}
if (!fwrite($file, $configfile))
{
 echo "Could not write to the config file.  Check file permissions and make sure the includes directory is writable.";
 exit;
}
fclose($file);
echo "Configuration file created.";

//setup the db with sql info
$sqlquery = file_get_contents('dbsetup.sql');

$sqlquery = preg_replace("/CREATE TABLE IF NOT EXISTS `/", "CREATE TABLE IF NOT EXISTS `".$_POST['DB_PREFIX'], $sqlquery);//add table prefix to sqlquery

$querys = explode("\n\n",$sqlquery);

foreach($querys as $i => $a)
{
  $sqlerror = FALSE;
  if (!mysql_query($a)) $sqlerror = TRUE;
}

if ($sqlerror == FALSE)
{
  echo "<p>Database setup complete.</p>\n";
} else {
  echo "<p>There was an error running SQL query to setup database.  The database is not setup.</p>";
	echo "<p>The following error was returned: ". mysql_error() ."</p>\n";
  exit;
}
//setup the db with initial config
$initconfig = file_get_contents('settings.sql');

$initconfig = preg_replace("/INSERT INTO `/", "INSERT INTO `".$_POST['DB_PREFIX'], $initconfig);//add table prefix to sqlquery

$settings = explode("\n",$initconfig);

foreach($settings as $i => $a)
{
  $sqlerror = FALSE;
  if (!mysql_query($a)) $sqlerror = TRUE;
}

if ($sqlerror == FALSE)
{
  echo "<p>Added initial settings to database.</p>\n";
} else {
  echo "<p>There was an error running SQL query to setup database.  The inital settings were not added to database.</p>";
	echo "<p>The following error was returned: ". mysql_error() ."</p>\n";
  exit;
}

//setup the admin user account

$query = "INSERT INTO `".$_POST['DB_PREFIX']."users` (`username`, `name`, `password`, `type`) VALUES ('".$_POST['HostUN']."', 'Admin', '".md5($_POST['HostPW'])."', 'host');";
if (mysql_query($query)) {
	  echo "<p>Added admin account to database. Step 2 is complete.</p>\n";
} else {
    echo "<p>There was an error running SQL query to create admin account. The admin account has not been setup.</p>";
    echo "<p>The following error was returned: ". mysql_error() ."</p>\n";
		exit;
}
?>
<form action='install_step3.php' method='post'>
<div style="text-align: right;">
<input type='submit' value='Go to Step 3'>
</div>
</form>
</body>
</html>