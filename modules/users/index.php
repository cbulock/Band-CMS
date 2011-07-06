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
	switch ($_REQUEST['act'])
	{
		case "post":
			if (!$_REQUEST['username'] || !$_REQUEST['password'] )
			{
				echo "<div class='error'>You must enter both a username and a password.</div>";
				break;
			}
			if(db_insert("users", array("username" => $_REQUEST['username'], "name" => $_REQUEST['name'], "password" => md5($_REQUEST['password']), "type" => "user")))
				echo "<div class='notice'>User successfully added!</div>";
			else
				echo "<div class='error'>There was a problem adding user.</div>";
		break;
		
		case "delete":
			$userindex = $_REQUEST['index'];
			$useritem = db_get_item("users", $userindex);
			if ($useritem['type'] == 'host')
			{
			 echo "<div class='error'>Can not delete host user.</div>";
			 break;
			}
			if(db_delete("users", $_REQUEST['index']))
				echo "<div class='notice'>User successfully removed!</div>";
			else
				echo "<div class='error'>There was a problem removing user.</div>";
		break;
		
		case "modify":
			if(isset($_REQUEST['username']))
			{
			 	$update['username'] = $_REQUEST['username'];
				$update['name'] = $_REQUEST['name'];
				if ($_REQUEST['password'])
					 $update['password'] = md5($_REQUEST['password']);
				if(db_update("users", $_REQUEST['index'], $update))
					echo "<div class='notice'>User successfully modified!</div>";
				else
					echo "<div class='error'>There was a problem modifying user.</div>";
			}
			else
			{
				$userindex = $_REQUEST['index'];
				$useritem = db_get_item("users", $userindex);
				?>
				<div class="admin_item">
					<div class='item_title'>Modify this user:</div>
          <div class='item_body'>
  					<form name="modify" action="?act=modify" method="post">
  					<table>
  						 <tr>
  							<td>
  								Name
  							</td>
  							<td>
  								<input size="50" type="text" name="name" value="<?php echo $useritem['name']; ?>" />
  							</td>
  						</tr>
							<tr>
  							<td>
  								E-mail address
  							</td>
  							<td>
  								<input size="50" type="text" name="username" value="<?php echo $useritem['username']; ?>" />
  							</td>
  						</tr>
            	<tr>
            		<td>
            			Password
            		</td>
            		<td>
            			<input colspan="3" size="50" type="password" name="password" value="" />
            		</td>
            	</tr>
  						<tr>
  							<td>
  							</td>
  							<td>
  								<br />
									<input type="hidden" name="index" value="<?php echo $userindex; ?>">
  								<input type="submit" name="submit" value="Update this user" />
  								</form>
  							</td>
  						</tr>
  					</table>
            <br />
            <table>
            	<tr>
              	<td>
                	<a href="index.php">Cancel</a>
                </td>
                <td align="right">
                	<?if ($useritem['type'] <> 'host') echo "<a href='index.php?act=delete&amp;index=". $userindex. "'>Delete this user</a>";?>
                </td>
              </tr>
            </table>
          </div>
				</div>
				<?php
			}
		break;
	}
	if($act != "modify" || isset($_REQUEST['name']))
	{
	?>
	
	<div class="admin_item">
		<div class="item_title">Create a new user:</div>
    <div class='item_body'>
  		<form name="post" action="?act=post" method="post">
  		<table>
  			<tr>
  				<td>
  					Name
  				</td>
  				<td>
  					<input size="50" type="text" name="name" value="">
  				</td>
  			</tr>
  			<tr>
  				<td>
  					E-mail address
  				</td>
  				<td>
  					<input size="50" type="text" name="username" value="">
  				</td>
  			</tr>
  			<tr>
  				<td>
  					Password
  				</td>
  				<td>
  					<input size="50" type="password" name="password" value="">
  				</td>
  			</tr>
  			<tr>
  				<td colspan="2" align="right">
  					<input type="submit" name="submit" value="Submit" />
  				</td>
  			</tr>
  		</table>
  		</form>
    </div>
	</div>
	<?php
	}


  echo "<div class='admin_item'>";
  echo "<div class='item_title'>" . USERS_NAME . "</div><div class='item_body'>";
  
  $usersArray = db_get_table("users", "1", "`index` ASC");
    
  if(!$usersArray)
  {
  	echo "<i>No users</i>";
  }
  else
  {
  	$toggle = true;
    echo "<table>";
    echo "<tr><td>Name</td><td>E-mail address</td><td>Type</td></tr>";
    foreach($usersArray as $item)
    {
    	if($toggle)
    		$class = "eventable";
    	else
    		$class = "oddtable";
    	echo "<tr><td class='" . $class . "' align='left'>";
    	echo $item['name'];
    	echo "</td><td class='" . $class . "' align='left'>";
    	echo $item['username'];
    	echo "</td><td class='" . $class . "' align='left'>";
      echo $item['type'];
      echo "</td><td align='center'><a href='?act=modify&index=" . $item['index'] . "'>Modify</a>";
      echo "</td></tr>";
      $toggle = !$toggle;
    }
    echo "</table>";
  }
  
  echo "</div></div>";
}
else
{
	header('location: http://'.DOMAIN_NAME . SCRIPT_FOLDER.'/admin/');
}

include('../../includes/footer.php');
?>