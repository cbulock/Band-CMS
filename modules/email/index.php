<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
include('../../includes/config.php');

$auth = authenticate();

if($auth['host'])
{
	include('../../includes/header.php');
	$act = $_GET['act'];
	
	switch ($act)
	{
		case "delete":
			$ok = TRUE;
			$file = file("http://" . CP_USERNAME . ":" . CP_PASSWORD . "@" . CP_HOSTNAME . ":2082/frontend/x/mail/dodelfwd.html?email=" . $email);
			if (!$file)
				$ok = FALSE;
			if($ok)
				echo "<div class='notice'>Email forward deleted.</div>";
			else
				echo "<div class='error'>Failed to delete email forward.</div>";
		break;
		case "post":
			$ok = FALSE;
			$pattern = "/\"2\">(.+?)<\/font>/";
			$file = file("http://" . CP_USERNAME . ":" . CP_PASSWORD . "@" . CP_HOSTNAME . ":2082/frontend/x/mail/fwds.html");
			$filetext = implode("", $file);
			preg_match_all($pattern, $filetext, $matches);
			if((count($matches[1]) / 2 < EMAIL_FORWARDS) || (EMAIL_FORWARDS == 0))
			{	
				$file = file("http://" . CP_USERNAME . ":" . CP_PASSWORD . "@" . CP_HOSTNAME . ":2082/frontend/x/mail/doaddfwd.html?email=$email&domain=" . DOMAIN_NAME . "&forward=$forward");
				if ($file)
					$ok = TRUE;
			}
			if ($ok)
				echo "<div class='notice'>Email forward added - " . $email . "@". DOMAIN_NAME . "</div>";
			else
				echo "<div class='error'>Failed to add email forward - " . $email . "@" . DOMAIN_NAME . "</div>";
		break;
		
		case "changedefault":
    	$forward = $_REQUEST['forward'];
			$ok = TRUE;
			$file = file("http://" . CP_USERNAME . ":" . CP_PASSWORD . "@" . CP_HOSTNAME . ":2082/frontend/x/mail/dosetdef.html?domain=" . DOMAIN_NAME . "&forward=$forward");
			if (!$file)
				$ok = FALSE;
			if ($ok)
      {
      	if(strpos($forward, ":", 1))
        	echo "<div class='notice'>Default address removed.</div>";
        else
					echo "<div class='notice'>Default address changed to " . $forward . "</div>";
      }
			else
				echo "<div class='error'>Failed to change default address - " . $forward . "</div>";
		break;
	}
	
	
	//List forwarders here
	$forwardsArray = cp_get_forwards();
	if($forwardsArray)
	{
		echo "<div class='admin_item'><div class='item_title'>Existing forwards:</div><div class='item_body'><table>";
		$toggle = true;
		foreach($forwardsArray as $item)
		{
			if($toggle)
				$class = "eventable";
			else
				$class = "oddtable";
			echo "<tr><td class='" . $class . "'>&nbsp;<strong>" . $item['address'] . "</strong></td>";
			if($item['forward'] == ":fail:")
			{
				echo "<td colspan=2 class='" . $class . "'>&nbsp;is returned to it's sender.</td>";
			}
			else
			{
				echo "<td class='" . $class . "'>&nbsp;forwards to </td><td class='" . $class . "'>&nbsp;<strong>" . $item['forward'] . "</strong>";
			}
			echo "</td><td align='center'><a href='?act=delete&email=" . $item['address'] . "=" . urlencode($item['forward']) . "'>Delete</a></td></tr>";
			$toggle = !$toggle;
		}
		echo "</table></div></div>";
	}
	else
	{
		if(is_null($forwardsArray))
			echo "<div class='error'>Could not access email information from the server, check cPanel settings on the config page.</div>";
		else
			echo "<div class='admin_item'><div class='item_title'>Existing forwards:</div><div class='item_body'><i>No email forwards specified.</i></div></div>";
	}
	
	if (!is_null($forwardsArray))
	{
  	//Add forwarder
  	if((count($forwardsArray) < EMAIL_FORWARDS) || (EMAIL_FORWARDS == 0))
  	{
  		echo "<div class='admin_item'><div class='item_title'>Add an email forward:</div><div class='item_body'>";
 
      	if(EMAIL_FORWARDS != 0)
        	echo "You are using " . count($forwardsArray) . " out of " . EMAIL_FORWARDS . " available forwards.<br /><br />";
      ?>
    <form method="POST" action="?act=post">
    <input type="text" name="email">@<?php echo DOMAIN_NAME; ?>&nbsp;&nbsp;>>>&nbsp;&nbsp;<input type="text" name="forward"> <input type="submit" value="Add">
    </form>
    </div>
    </div>
  	<?php
  	}
  	else
  	{
  		echo "<div class='admin_item'><div class='item_title'>Add an email forward:</div><div class='item_body'>All available forwards are currently in use.</div></div>";
  	}
  
  	//Default Address
  	$defaultaddress = cp_get_default();
  
  	echo "<div class='admin_item'><div class='item_title'>Default Address:</div><div class='item_body'>All other mail ";
  	if(strpos($defaultaddress, ":", 1))
  		echo "is returned to it's sender.";
  	else
  		echo "is forwarded to <strong>" . $defaultaddress . "</strong>";
  	?>
  		<br />
  		<br />
  		<form method="POST" action="?act=changedefault">
  		New default address: <input type="text" name="forward"> <input type="submit" value="Change">
  		</form>
      <br />
      <?php
      if(!strpos($defaultaddress, ":", 1))
      {
      	echo "<a href='?act=changedefault&amp;forward=%3afail%3a'>Remove default address</a>";
      }
  		echo "</div></div>";

	}
}
else
{
	header('location: http://' . DOMAIN_NAME . SCRIPT_FOLDER . '/admin/');	
}

include('../../includes/footer.php');

?>