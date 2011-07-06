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
		case "delete":
			if(db_delete("links_category", $_REQUEST['index']))
      {
      	db_update('links', $_REQUEST['index'], array('category' => 0), 'category');
				echo "<div class='notice'>Category successfully deleted.</div>";
      }
			else
				echo "<div class='error'>There was a problem deleting your category.</div>";    	
    break;
    
    case "post":
    	if(db_insert("links_category", array("name" => $_REQUEST['name'])))
				echo "<div class='notice'>Category successfully created.</div>";
			else
				echo "<div class='error'>There was a problem creating your category.</div>";
    break;
    
    case "modify":
    	if(isset($_REQUEST['name']))
      {
    		if(db_update("links_category", $_REQUEST['index'], array("name" => $_REQUEST['name'])))
    			echo "<div class='notice'>Category successfully modified.</div>";
		    else
    			echo "<div class='error'>There was a problem modifying your category.</div>";
			}
			else
			{
				$categoryindex = $_REQUEST['index'];
				$categoryitem = db_get_item("links_category", $categoryindex);
				?>
				<div class="admin_item">
					<div class='item_title'>Modify this category:</div>
          <div class='item_body'>
					<form name="modify" action="?act=modify" method="post">
					<table>
						<tr>
							<td>
								Name
							</td>
							<td>
								<input size="50" type="text" name="name" value="<?php echo $categoryitem['name']; ?>">
							</td>
						</tr>
						<tr>
							<td>
							</td>
							<td>
								<br />
                <input type="hidden" name="index" value="<?php echo $categoryindex; ?>">
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
              	<a href="?act=delete&index=<?php echo $categoryindex; ?>">Delete this category</a>
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
		<div class='item_title'>Create a new category:</div>
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
        	<a href="index.php">Return to the links page</a>
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
  
  $categoryArray = db_get_table("links_category");
  
  echo "<div class='admin_item'>";
  echo "<div class='item_title'>Existing Categories</div><div class='item_body'>";
  foreach($categoryArray as $item)
  {
  	echo "(<a href='?act=modify&index=" . $item['index'] . "'>Edit</a>) - ";
  	echo $item['name'];
  	echo "<br /><br />";
  }
  echo "</div></div>";

}
include('../../includes/footer.php');
?>