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

  <title>CMS Installer - Step 3</title>
<style>
body {
font-family:Helvetica,Arial,sans-serif;
}
</style>
</head>
<body>

<h1>CMS Installer</h1>
<h3>Step 3 - Configure Setup</h3>

<p>These settings can be changed at a later time.  <strong>DOMAIN NAME AND SCRIPT FOLDER MUST BE CORRECT OR SETUP WILL FAIL.</strong></p>
<p>Click on the titles to see a description of the configuration setting.</p>

<form action="install_step4.php" method='post'>
<?php

$categoryArray = db_get_table("config_category", 1, "`index` ASC");
foreach($categoryArray as $item)
{
  $configArray = db_get_table("config", "`category` = " . $item['index'] . " AND 1", "`index` ASC");
	if($configArray)
	{
  	echo "<div class='admin_item'>\n";
		echo "<h5 class='item_title'>" . $item['name'] . "</h5><div class='item_body'>\n";
		echo "<table>\n";
		$toggle = true;
		foreach($configArray as $config)
		{
			if($toggle)
				$class = "eventable";
			else
				$class = "oddtable";
			echo "<tr><td class='" . $class . "'>\n";
			echo "<a href='#' onclick=\"javascript:window.open('descrip.php?index=".$config['index']."','descrip','width=600,height=300,toolbar=0,scrollbars=1');return false;\">".$config['pretty_name']."</a>\n";
			echo "</td><td class='" . $class . "' align='right'>\n";
      echo "<input size='50' type='text' name='".$config['index']."' value='".$config['value']."'>\n";
			echo "</td></tr>\n";
			$toggle = !$toggle;
		}
		echo "</table>\n";
    echo "</div></div>";
	}
}
?>
<div style="text-align: right;">
<input type='submit' value='Go to Step 3'>
</div>
</form>
</body>
</html>