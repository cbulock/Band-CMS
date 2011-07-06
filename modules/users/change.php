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
 if ($_REQUEST['new_password'] <> $_REQUEST['new_password2'])
 {
 	echo "<div class='error'>New passwords do not match.</div>";
 }
 else
 {
   $useritem = db_get_item("users", $_REQUEST['username'], "username");
	 if ($useritem)
   {
   	if ($useritem['password'] == md5($_REQUEST['password']) || $useritem['password'] == $_POST['id'])
		{
      if(db_update("users", $_REQUEST['username'], array("password" => md5($_REQUEST['new_password'])), "username"))
      { 
        	echo "<div class='admin_item' style='width:260; margin-top: 10%;'>";
  				echo "<div class='item_title'>New password sent</div>";
  				echo "<div class='item_body'>";
					echo "Your password has been changed. If you have problems with this new password, please contact your web host.<br /><br />\n";
      		echo "<a href='../../admin/'>Return to login screen.<a>";
					echo "</div></div>";
				  $showMenu = FALSE;
      }
      else
      {
      	echo "<div class='error'>There was a problem changing password. Password has not been changed.</div>";
      }
		}
		else
		{
		echo "<div class='error'>Incorrect password.</div>";
		}
   }
   else
   {
   	echo "<div class='error'>E-mail address not found.</div>";
   }
 }
}
if ($showMenu)
{
  if ($_REQUEST['id'])
		 $useritem = db_get_item("users", $_REQUEST['id'], "password")
	?>
  <div class="admin_item" style="width:260; margin-top: 10%;">
  <div class="item_title">Change Password</div>
  <div class="item_body">
  <form name="change" action="change.php" method="post">
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
  			<input type="text" name="username" <?php if ($auth['loggedIn']) echo "value='".$auth['username']."' "; if ($useritem['username']) echo "value='".$useritem['username']."' ";?>/>
  		</td>
  	</tr>
		<?php
		if (!$_REQUEST['id']) {
		?>
  	<tr>
  		<td>
  			Current Password
  		</td>
  		<td>
  			<input type="password" name="password" />
  		</td>
  	</tr>
		<?php
		}
		?>
  	<tr>
  		<td>
  			New Password
  		</td>
  		<td>
  			<input type="password" name="new_password" />
  		</td>
  	</tr>
  	<tr>
  		<td>
  			Re-type New Password
  		</td>
  		<td>
  			<input type="password" name="new_password2" />
  		</td>
  	</tr>
  	<tr>
  		<td align="right" colspan="2">
				<?php if ($_REQUEST['id']) echo "<input type='hidden' name='id' value='".$_REQUEST['id']."' />\n"; ?>
  			<input type="submit" value="Change Password">
				</form>
  		</td>
  	</tr>
  </table>
  </div>
  </div>
	<?php	
}
include('../../includes/footer.php');

?>