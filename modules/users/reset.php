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

include('../../includes/header.php');

$showMenu = TRUE;

if ($_REQUEST['username'])
{
  if (db_get_item("users", $_REQUEST['username'], "username"))
	{
  	$new_pass = generatePassword(8);
  	
    $mail = "Your password for ".$_REQUEST['username']." has been reset.\n\n";
		$mail .= "To setup new password:\n";
		$mail .= "http://".DOMAIN_NAME . SCRIPT_FOLDER."/modules/users/change.php?id=".md5($new_pass)."\n";
    if (mail($_REQUEST['username'],'New Password for '.SITE_NAME, $mail, "From: ".HOST_EMAIL))
    {
  		if(db_update("users", $_REQUEST['username'], array("password" => md5($new_pass)), "username"))
			{ 
      	echo "<div class='admin_item' style='width:260; margin-top: 10%;'>";
				echo "<div class='item_title'>New password sent</div>";
				echo "<div class='item_body'>";
				echo "Your new password was sent to ". $_REQUEST['username'] .". If you have problems with this new password, please contact your web host.<br /><br />\n";
				echo "<a href='../../admin/'>Return to login screen.<a>";
				echo "</div></div>";
				$showMenu = FALSE;
  		}
			else
			{
  			echo "<div class='error'>There was a problem resetting password. Password has not been reset.</div>";
			}
    }
  	else
  	{
  		echo "<div class='error'>There was a problem mailing password. Password has not been reset.</div>";
  	}
	}
	else
	{
	 echo "<div class='error'>E-mail address not found.</div>";
	}
}
if ($showMenu)
{
  ?>
  <div class="admin_item" style="width:260; margin-top: 10%;">
  <div class="item_title">Reset Password</div>
  <div class="item_body">
  <form name="reset" action="reset.php" method="post">
  <table>
  	<tr>
  		<td colspan="2" align="center">
  			<?php echo $auth['message']; ?>
  		</td>
  	</tr>
  	<tr>
  		<td>
  			E-mail address
  		</td>
  		<td>
  			<input type="text" name="username" />
  		</td>
  	</tr>
  	<tr>
  		<td align="right" colspan="2">
  			<input type="submit" value="Send Reset E-mail">
  		</td>
  	</tr>
  </table>
  </div>
  </div>
  <?php	
}
include('../../includes/footer.php');

?>