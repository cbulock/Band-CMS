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

$act = $_REQUEST['act'];

if($auth['loggedIn'])
{
	include('../../includes/header.php');
	
	switch ($act)
	{    
		case "modify":
    	if($auth['host'])
      	$data = array("value" => $_REQUEST['value'], "changeable" => $_REQUEST['changeable']);
      else
        $data = array("value" => $_REQUEST['value']);
			if(isset($_REQUEST['value']))
			{
				if(db_update("config", $_REQUEST['index'], $data))
					echo "<div class='notice'>Config item successfully modified.</div>";
				else
					echo "<div class='error'>There was a problem modifying your config item.</div>";
			}
			else
			{
				$configindex = $_REQUEST['index'];
				$configitem = db_get_item("config", $configindex);
				?>
				<div class="admin_item">
					<div class='item_title'><?php echo $configitem['pretty_name'] ?></div><div class="item_body">
					<form name="modify" action="?act=modify" method="post">
          <?php
          echo nl2br(bb_parse($configitem['description']));
          echo "<br /><br />";
          if($auth['host'])
          {
          ?>
     				<input type='checkbox' name='changeable' <?php if($configitem['changeable']) echo "checked=checked "; ?>/> - Changeable by user<br /><br />
          <?php
          }
          ?>
					Value:	<input size="50" type="text" name="value" value="<?php echo $configitem['value']; ?>">
          <input type="hidden" name="index" value="<?php echo $configindex; ?>">
  				<input type="submit" name="submit" value="Update this item">
          <br />
          <br />
         	<a href="index.php">Cancel</a>
				</div></div>
				<?php
			}
		break;
	}
  if($auth['host'])
  	$where = 1;
  else
  	$where = "`changeable` = 'on'";
  $categoryArray = db_get_table("config_category", 1, "`index` ASC");
  foreach($categoryArray as $item)
  {
    $configArray = db_get_table("config", "`category` = " . $item['index'] . " AND " . $where, "`index` ASC");
  	if($configArray)
  	{
    	echo "<div class='admin_item'>";
  		echo "<div class='item_title'>" . $item['name'] . "</div><div class='item_body'>";
  		echo "<table>";
  		$toggle = true;
  		echo "<tr><td>name</td><td align='right'>value</td></tr>";
  		foreach($configArray as $config)
  		{
  			if($toggle)
  				$class = "eventable";
  			else
  				$class = "oddtable";
  			echo "<tr><td class='" . $class . "'>";
  			echo $config['pretty_name'];
  			echo "</td><td class='" . $class . "' align='right'>";
        echo $config['value'];
  			echo "</td><td align='center'><a href='?act=modify&index=" . $config['index'] . "'>Modify</a></td></tr>";
  			$toggle = !$toggle;
  		}
  		echo "</table>";
      echo "</div></div>";
  	}
  }
  
  
}
else
{
	header('location: http://'.DOMAIN_NAME . SCRIPT_FOLDER.'/admin/');
}

include('../../includes/footer.php');

?>