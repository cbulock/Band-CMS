<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
include('../includes/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>CMS Installer - Step 4</title>
<style>
body {
font-family:Helvetica,Arial,sans-serif;
}
</style>
</head>
<body>

<h1>CMS Installer</h1>
<h3>Step 4 - Complete</h3>

<?php
$error = FALSE;
foreach($_POST as $index => $value)
{
if (!db_update('config',$index,array("value" => $value))) $error = TRUE;
}
if ($error == FALSE)
{
  echo "<p>Settings updated.  CMS install is complete!</p>\n";
	echo "<p><strong>For security reasons, make sure to remove the 'install' folder on the server at this time.  It will no longer be needed.</strong></p>\n";
} else {
  echo "<p>There was an error running SQL query to update settings in the database.  The settings were not updated in the database.</p>";
	echo "<p>The following error was returned: ". mysql_error() ."</p>\n";
}
?>
</body>
</html>