<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Content-type: text/html; charset=utf-8");

	$style_folder = DOMAIN_NAME . SCRIPT_FOLDER . "/styles";
  if(STYLESHEET_FOLDER != "")
  	$style_folder .= "/" . STYLESHEET_FOLDER;
    
?>

<html>
	<head>
		<title>
			<?php echo SITE_NAME; ?>
		</title>
		<link href="<?php echo 'http://' . $style_folder; ?>/style.css?<?php echo time(); ?>" type=text/css rel=stylesheet>
	</head>
	<body>
  <?php
  if($auth['loggedIn'])
  {
  	echo "<div name='adminmenu' id='adminmenu'>";
		if ($auth['name']) echo "Welcome, " . $auth['name'];
    if($auth['host'])
    {
      echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/email/index.php' target='site_body'>" . EMAIL_NAME . "</a>" . MENU_SEPERATOR;
			echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/users/index.php' target='site_body'>" . USERS_NAME . "</a>" . MENU_SEPERATOR;
  		echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/pages/index.php' target='site_body'>" . PAGES_NAME . "</a>" . MENU_SEPERATOR;
    }
		else
		{
		  echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/users/change.php' target='site_body'>" . CHANGE_PW_NAME . "</a>" . MENU_SEPERATOR;

		}
    echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/config/index.php' target='site_body'>Config</a>" . MENU_SEPERATOR;
    echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/admin/index.php?act=logout' target='_top'>Logout</a>";
		echo "</div>";
  }
  ?>