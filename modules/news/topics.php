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

if($auth['loggedIn'])
{
	switch ($act)
	{
		case "post":
			if(db_insert("topics", array("name" => $_REQUEST['name'])))
				echo "<div class='notice'>Topic successfully created.</div>";
			else
				echo "<div class='error'>There was a problem creating your topic.</div>";
		break;
		
		case "delete":
			if(db_delete("topics", $_REQUEST['index']))
			{
        db_update('news', $_REQUEST['index'], array('topic' => 0), 'topic');
				echo "<div class='notice'>Topic successfully deleted.</div>";
			} else {
				echo "<div class='error'>There was a problem deleting your topic.</div>";
			}
		break;
		
		case "modify":
			if(isset($_REQUEST['name']))
			{
				if(db_update("topics", $_REQUEST['index'], array("name" => $_REQUEST['name'])))
					echo "<div class='notice'>Topic successfully modified.</div>";
				else
					echo "<div class='error'>There was a problem modifying your topic.</div>";
			}
			else
			{
				$linkindex = $_REQUEST['index'];
				$linkitem = db_get_item("topics", $linkindex);
				?>
				<div class="admin_item">
					<div class='item_title'>Modify this topic:</div>
          <div class='item_body'>
					<form name="modify" action="?act=modify" method="post">
					<table>
						<tr>
							<td>
								Name
							</td>
							<td>
								<input size="50" type="text" name="name" value="<?php echo $linkitem['name']; ?>">
							</td>
						</tr>
						<tr>
							<td>
							</td>
							<td>
								<br />
                <input type="hidden" name="index" value="<?php echo $linkindex; ?>">
								<input type="submit" name="submit" value="Update this item">
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
              	<a href="?act=delete&index=<?php echo $linkindex; ?>">Delete this item</a>
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
		<div class='item_title'>Create a new topic:</div>
    <div class='item_body'>
		<form name="post" action="?act=post" method="post">
		<table>
			<tr>
				<td>
					Name
				</td>
				<td>
					<input colspan="2" size="50" type="text" name="name" value="">
				</td>
			</tr>
			<tr>
      	<td>
        	<a href="index.php">Return to the news page</a>
        </td>
				<td align="right">
					<input type="submit" name="submit" value="Submit">
				</td>
			</tr>
		</table>
		</form>
	</div>
  </div>
	<?php
	}
  
  $topicsArray = db_get_table("topics");
  
  echo "<div class='admin_item'>";
  echo "<div class='item_title'>Existing Topics</div>";
  echo "<div class='item_body'>";
  foreach($topicsArray as $item)
  {
  	echo "(<a href='?act=modify&index=" . $item['index'] . "'>Edit</a>) - ";
  	echo $item['name'];
  	echo "<br /><br />";
  }
  echo "</div></div>";

}
include('../../includes/footer.php');
?>