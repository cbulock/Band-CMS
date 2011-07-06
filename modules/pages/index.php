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
	switch ($_REQUEST['act'])
	{
		case "post":
			if(db_insert("pages", array("name" => $_REQUEST['name'], "menu_title" => $_REQUEST['menu_title'], "body" => $_REQUEST['body'], "url" => $_REQUEST['url'], "type" => $_REQUEST['type'], "hide" => $_REQUEST['hide'])))
				header('Location: ../../redirector.php?url=index.php?frame=modules/pages/?notice=posted');
			else
				$error = "There was a problem creating page.";
		break;
		
		case "delete":
			if(db_delete("pages", $_REQUEST['index']))
				header('Location: ../../redirector.php?url=index.php?frame=modules/pages/?notice=deleted');
			else
				$error = "There was a problem deleting page.";
		break;
		
		case "modify":
			if(isset($_REQUEST['name']))
			{
				if(db_update("pages", $_REQUEST['index'], array("name" => $_REQUEST['name'], "menu_title" => $_REQUEST['menu_title'], "body" => $_REQUEST['body'], "url" => $_REQUEST['url'], "type" => $_REQUEST['type'], "hide" => $_REQUEST['hide'])))
					header('Location: ../../redirector.php?url=index.php?frame=modules/pages/?notice=modified');
				else
					$error = "There was a problem modifying page.";
			}
			break;
	}
}

include('../../includes/header.php');

if ($_GET['menu_title']) {
	 $pagetitle = $_GET['menu_title'];
	 $pageitem = db_get_item("pages", $pagetitle, "menu_title");
	 if (!$_REQUEST['act']) $_REQUEST['act'] = 'modify';
	 $pageindex = $pageitem['index'];
}


if ($_GET['index']) {
  $pageindex = $_REQUEST['index'];
  $pageitem = db_get_item("pages", $pageindex);
	if (!$_REQUEST['act']) $_REQUEST['act'] = 'modify';
}

if($auth['host']) {
	switch ($_REQUEST['notice']) {
		case 'posted':
			$notice = "Page successfully created.";
		break;
		case 'deleted':
			$notice = "Page successfully deleted.";
		break;
		case 'modified':
			$notice = "Page successfully modified.";
		break;
	}	
	
	if ($notice)
		 echo "<div class='notice'>" . $notice . "</div>\n";
	if ($error)
		 echo "<div class='error'>" . $error . "</div>\n";
	if ($_REQUEST['act'] == "modify" &&	!isset($_REQUEST['name']))
	{
		?>
		<div class="admin_item">
			<div class='item_title'>Modify this page</div>
      <div class='item_body'>
			<form name="modify" action="<?php echo SCRIPT_FOLDER; ?>/modules/pages/?act=modify" method="post">
			<table>
				<tr>
					<td>
						Page Title
					</td><td></td>
					<td>
						<input size="50" type="text" name="name" value="<?php echo $pageitem['name']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						Menu Title
					</td><td></td>
					<td>
						<input size="50" type="text" name="menu_title" value="<?php echo $pageitem['menu_title']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						Page URL
					</td>
					<td>
						<input type="radio" name="type" value="url" <?php if ($pageitem['type'] == 'url') echo "checked='checked'"; ?> />
					</td>
					<td>
						<input size="50" type="text" name="url" value="<?php echo $pageitem['url']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						Page Body
					</td>
					<td>
						<input type="radio" name="type" value="text" <?php if ($pageitem['type'] == 'text') echo "checked='checked'"; ?> />
					</td>
					<td>
						<textarea name="body" rows=8 wrap="soft"><?php echo $pageitem['body']; ?></textarea>
					</td>
				</tr>
  			<tr>
    			<td>
    				Hide from menu
    			</td>
    			<td>
    				<input type='checkbox' name='hide' <?php if($pageitem['hide']) echo "checked=checked "; ?>/>
    			</td><td></td>
    		</tr>
				<tr>
					<td>
					</td><td></td>
					<td>
						<br />
              <input type="hidden" name="index" value="<?php echo $pageindex; ?>">
						<input type="submit" name="submit" value="Update this item">
						</form>
					</td>
				</tr>
			</table>
        <br />
        <table>
        	<tr>
          	<td>
            	<a href="<?php echo SCRIPT_FOLDER; ?>/modules/pages/">Cancel</a>
            </td>
            <td align="right">
            	<a href="<?php echo SCRIPT_FOLDER; ?>/modules/pages/?act=delete&index=<?php echo $pageindex; ?>">Delete this item</a>
            </td>
          </tr>
        </table>
		</div>
    </div>
		<?php
	}

	if($_REQUEST['act'] != "modify" || isset($_REQUEST['name']))
	{
	?>
	
	<div class="admin_item">
		<div class='item_title'>Create a new page:</div>
    <div class='item_body'>
		<form name="post" action="?act=post" method="post">
		<table>
			<tr>
				<td>
					Page Title
				</td><td></td>
				<td>
					<input size="50" type="text" name="name" value="" />
				</td>
			</tr>
			<tr>
				<td>
					Menu Title
				</td><td></td>
				<td>
					<input size="50" type="text" name="menu_title" value="" />
				</td>
			</tr>
			<tr>
				<td>
					Page URL
				</td>
				<td>
					<input type="radio" name="type" value="url" />
				</td>
				<td>
					<input size="50" type="text" name="url" value="http://" />
				</td>
			</tr>
  		<tr>
  			<td>
  				Page Body
  			</td>
  			<td>
  				<input type="radio" name="type" value="text" checked="checked" />
				</td>
				<td>
					<textarea name="body" rows=8 wrap="soft"></textarea>
  			</td>
  		</tr>
			<tr>
  			<td>
  				Hide from menu
  			</td>
  			<td>
  				<input type='checkbox' name='hide' />
  			</td><td></td>
  		</tr>
			<tr>
				<td align="right" colspan="3">
					<input type="submit" name="submit" value="Submit">
				</td>
			</tr>
		</table>
		</form>
	</div>
  </div>
	<?php
	}
}

if ($_REQUEST['index'] || $_GET['menu_title']) {
	echo "<div class='pages_item'>";
	echo "<div class='item_title'>" . $pageitem['name'] . "</div><div class='item_body'>";
	echo html_entity_decode($pageitem['body']);
	echo "</div></div>";

}

if($auth['host']) {
  $pagesArray = db_get_table("pages", "1", "`index` DESC");
  
  echo "<div class='admin_item'>";
  echo "<div class='item_title'>" . PAGES_NAME . "</div><div class='item_body'>";
	if(!$pagesArray)
  {
  	echo "<i>No pages</i>";
  }
  else
  {
  	$toggle = true;
    echo "<table>";
		echo "<tr><td>name</td><td align='center'>menu title</td><td align='right'>hidden</td></tr>";
		foreach($pagesArray as $item)
		{
			if($toggle)
				$class = "eventable";
			else
				$class = "oddtable";
			echo "<tr><td class='" . $class . "'>";
			echo $item['name'];
			echo "</td><td class='" . $class . "' align='center'>";
      echo $item['menu_title'];
			echo "</td><td class='" . $class . "' align='right'>";
      echo $item['hide'];
			echo "</td><td align='center'><a href='" . SCRIPT_FOLDER . "/modules/pages/?act=modify&index=" . $item['index'] . "'>Modify</a></td></tr>";
			$toggle = !$toggle;
		}
		echo "</table>";
  }
	
  echo "</div></div>";
}



include('../../includes/footer.php');
?>