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

// *** This is the start of the pagination code - this is needed here becase if a
// *** redirect is needed due to an improper page number passed in a url argument,
// *** it has to happen before any output
$page = 0;
if (isset($_REQUEST['page']))
	$page = intval($_REQUEST['page']) - 1;

$numberOfLinks = db_count("links");

if ($page * LINKS_PER_PAGE > $numberOfLinks)
	header('location: index.php');

include('../../includes/header.php');
// *** End of the first part of the pagination code


if($auth['loggedIn'])
{
	switch ($act)
	{
		case "post":
			if(db_insert("links", array("name" => $_REQUEST['name'], "linkurl" => $_REQUEST['linkurl'], "category" => $_REQUEST['category'], "description" => $_REQUEST['description'])))
				echo "<div class='notice'>Link successfully created.</div>";
			else
				echo "<div class='error'>There was a problem creating your link.</div>";
		break;
		
		case "delete":
			if(db_delete("links", $_REQUEST['index']))
				echo "<div class='notice'>Link successfully deleted.</div>";
			else
				echo "<div class='error'>There was a problem deleting your link.</div>";
		break;
		
		case "modify":
			if(isset($_REQUEST['linkurl']))
			{
				if(db_update("links", $_REQUEST['index'], array("name" => $_REQUEST['name'], "linkurl" => $_REQUEST['linkurl'], "category" => $_REQUEST['category'], "description" => $_REQUEST['description'])))
					echo "<div class='notice'>Link successfully modified.</div>";
				else
					echo "<div class='error'>There was a problem modifying your link.</div>";
			}
			else
			{
				$linkindex = $_REQUEST['index'];
				$linkitem = db_get_item("links", $linkindex);
				?>
				<div class="admin_item">
					<div class='item_title'>Modify this link:</div>
          <div class='item_body'>
					<form name="modify" action="?act=modify&amp;page=<?php echo $page + 1; ?>" method="post">
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
								URL
							</td>
							<td>
								<input size="50" type="text" name="linkurl" value="<?php echo $linkitem['linkurl']; ?>">
							</td>
						</tr>
      			<tr>
      				<td>
      					Category
      				</td>
      				<td>
      					<select name="category">
                	<?php
                	echo "<option value='0'";
                  if($linkitem['category'] == 0) echo " selected";
                  echo ">" . LINKS_DEFAULT_CATEGORY . "</option>";
      						
      						foreach(db_get_table("links_category") as $item)
      						{
      							echo "<option value='" . $item['index'] . "'";
                    if($linkitem['category'] == $item['index']) echo " selected";
                    echo ">" . $item['name'] . "</option>";
      						}
      						?>
      					</select>
      				</td>
      			</tr>
						<tr>
							<td>
								Description
							</td>
							<td>
								<textarea name="description" rows=4 wrap="soft"><?php echo $linkitem['description']; ?></textarea>
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
              	<a href="index.php?page=<?php echo $page + 1; ?>">Cancel</a>
              </td>
              <td align="right">
              	<a href="?act=delete&amp;page=<?php echo $page + 1; ?>&amp;index=<?php echo $linkindex; ?>">Delete this item</a>
              </td>
            </tr>
          </table>
				</div>
        </div>
				<?php
			}
		break;
	}
	if($act != "modify" || isset($_REQUEST['linkurl']))
	{
	?>
	
	<div class="admin_item">
		<div class='item_title'>Create a new link:</div>
    <div class='item_body'>
		<form name="post" action="?act=post&amp;page=<?php echo $page + 1; ?>" method="post">
		<table>
			<tr>
				<td>
					Name
				</td>
				<td>
					<input colspan="3" size="50" type="text" name="name" value="">
				</td>
			</tr>
			<tr>
				<td>
					URL
				</td>
				<td>
					<input colspan="3" size="50" type="text" name="linkurl" value="http://">
				</td>
			</tr>
			<tr>
				<td>
					Category
				</td>
				<td colspan="2">
					<select name="category">
          	<option value='0'><?php echo LINKS_DEFAULT_CATEGORY; ?></option>
						<?php
						foreach(db_get_table("links_category", "1", "`index` ASC") as $item)
						{
							echo "<option value='" . $item['index'] . "'>" . $item['name'] . "</option>";
						}
						?>
					</select>
          <a href="categories.php">Modify Categories</a>
				</td>
			</tr>
  		<tr>
  			<td>
  				Description
  			</td>
  			<td>
  				<textarea name="description" rows=4 wrap="soft"></textarea>
  			</td>
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

if (db_count("links") > 0)
{
  $linksArray = db_get_table("links", "`category` = 0");
  if($linksArray)
  {
  	echo "<div class='links_item'>";
  	echo "<div class='item_title'>" . LINKS_DEFAULT_CATEGORY . "</div><div class='item_body'>";
    foreach($linksArray as $item)
    {
    	if($auth['loggedIn']) echo "(<a href='?act=modify&amp;page=" . intval($page + 1) . "&amp;index=" . $item['index'] . "'>Edit</a>) - ";
    	echo "<a href='" . $item['linkurl'] . "' target='_new'>";
    	if($item['name'])
    		echo $item['name'];
    	else
    		echo $item['linkurl'];
      echo "</a>";
      if($item['description'])
      	echo " - " . nl2br(bb_parse($item['description']));
    	echo "<br /><br />";
    }
    echo "</div></div>";
  }
  
  
  
  $categoryArray = db_get_table("links_category");
  foreach($categoryArray as $category)
  {
    $linksArray = db_get_table("links", "`category` = " . $category['index']);
    if($linksArray)
    {
    	echo "<div class='links_item'>";
    	echo "<div class='item_title'>" . $category['name'] . "</div><div class='item_body'>";
      foreach($linksArray as $item)
      {
      	if($auth['loggedIn']) echo "(<a href='?act=modify&amp;page=" . intval($page + 1) . "&amp;index=" . $item['index'] . "'>Edit</a>) - ";
      	echo "<a href='" . $item['linkurl'] . "' target='_new'>";
      	if($item['name'])
      		echo $item['name'];
      	else
      		echo $item['linkurl'];
        echo "</a>";
        if($item['description'])
        	echo " - " . nl2br(bb_parse($item['description']));
      	echo "<br /><br />";
      }
      echo "</div></div>";
    }
  }
}
else
{
  echo "<div class='links_item'>";
  echo "<div class='item_title'>" . LINKS_NAME . "</div>";
  echo "<div class='item_body'><em>There are no links to display.</em></div></div>";
}

include('../../includes/footer.php');
?>