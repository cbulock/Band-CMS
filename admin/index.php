<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
include('../includes/config.php');

if($_GET['act'] == "logout")
{
	logout();
	header('location: http://'.DOMAIN_NAME . SCRIPT_FOLDER.'/');
}

$auth = authenticate();

if($auth['loggedIn'])
{
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header('location: ../redirector.php?url=index.php');
}
else
{
	include('../includes/header.php');
?>
<div class="admin_item" style="width:260; margin-top: 10%;">
<div class="item_title">Please log in.</div>
<div class="item_body">
<form name="login" action="index.php" method="post" enctype="multipart/form-data">
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
			<input type="text" name="new_user"<?php if(isset($_REQUEST['new_user'])) echo " value='" . htmlspecialchars($_REQUEST['new_user']) . "'"; ?>
		</td>
	</tr>
	<tr>
		<td>
			Password
		</td>
		<td>
			<input type="password" name="new_password">
		</td>
	</tr>
	<tr>
		<td align="right" colspan="2">
			<input type="hidden" name="mod" value="admin">
			<input type="submit" value="Login">
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<a href="../modules/users/reset.php">Forgot Password</a>
		</td>
	</tr>
</table>
</div>
</div>
<?php	
	
}
	
include('../includes/footer.php');

?>