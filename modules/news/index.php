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

$numberOfNews = db_count("news");
$numberOfPages = ceil($numberOfNews / NEWS_PER_PAGE);

if ($page * NEWS_PER_PAGE > $numberOfNews)
	header('location: index.php');
// *** End of the first part of the pagination code

include('../../includes/header.php');



if($auth['loggedIn'])
{
	switch ($act)
	{
		case "post":
			if(db_insert("news", array("topic" => $_REQUEST['topic'], "title" => $_REQUEST['title'], "body" => $_REQUEST['body'], "timestamp" => time())))
			{
				echo "<div class='notice'>News entry successfully posted.</div>";
				$numberOfNews = db_count("news");
				$numberOfPages = ceil($numberOfNews / NEWS_PER_PAGE);
			}
			else
			{
				echo "<div class='error'>There was a problem posting your news entry.</div>";
			}
		break;
		
		case "delete":
			if(db_delete("news", $_REQUEST['index']))
			{
				echo "<div class='notice'>News entry successfully deleted.</div>";
				$numberOfNews = db_count("news");
				$numberOfPages = ceil($numberOfNews / NEWS_PER_PAGE);
				if ($page > $numberOfPages - 1) $page = $numberOfPages - 1;
				if ($numberOfNews == 0) $page = 0;
			}
			else
			{
				echo "<div class='error'>There was a problem deleting your news entry.</div>";
			}
		break;
		
		case "modify":
			if(isset($_REQUEST['title']))
			{
				if(db_update("news", $_REQUEST['index'], array("topic" => $_REQUEST['topic'], "title" => $_REQUEST['title'], "body" => $_REQUEST['body'])))
					echo "<div class='notice'>News entry successfully modified.</div>";
				else
					echo "<div class='error'>There was a problem modifying your news entry.</div>";
			}
			else
			{
				$newsindex = $_REQUEST['index'];
				$newsitem = db_get_item("news", $newsindex);
				?>
				<div class="admin_item">
					<div class='item_title'>Modify this news entry:</div>
          <div class='item_body'>
					<form name="modify" action="?act=modify&amp;page=<?php echo $page + 1; ?>" method="post">
					<table>
						<tr>
							<td>
								Title
							</td>
							<td>
								<input size="50" type="text" name="title" value="<?php echo $newsitem['title']; ?>">
							</td>
						</tr>
						<tr>
							<td>
								Body
							</td>
							<td>
								<textarea name="body" rows=8 wrap="soft"><?php echo $newsitem['body']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td>
								Topic
							</td>
							<td>
								<select name="topic">
									<option value='0'>(none)</option>
									<?php
									foreach(db_get_table("topics") as $item)
									{
										echo "<option value='" . $item['index'] . "'";
										if($newsitem['topic'] == $item['index'])
											echo " selected";
										echo ">" . $item['name'] . "</option>";
									}
									?>
								</select>
								<a href="topics.php">Modify Topics</a>
							</td>
            </tr>
						<tr>
							<td>
							</td>
							<td>
								<br />
                <input type="hidden" name="index" value="<?php echo $newsindex; ?>">
								<input type="submit" name="submit" value="Update this entry">
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
              	<a href="index.php?act=delete&amp;page=<?php echo $page + 1; ?>&amp;index=<?php echo $newsindex; ?>">Delete this entry</a>
              </td>
            </tr>
          </table>
				</div>
        </div>
				<?php
			}
		break;
	}
	if($act != "modify" || isset($_REQUEST['title']))
	{
	?>
	
	<div class="admin_item">
		<div class='item_title'>Post a new entry:</div>
    <div class='item_body'>
		<form name="post" action="?act=post&amp;page=<?php echo $page + 1; ?>" method="post">
		<table>
			<tr>
				<td>
					Title
				</td>
				<td>
					<input colspan="3" size="50" type="text" name="title" value="">
				</td>
			</tr>
			<tr>
				<td>
					Body
				</td>
				<td colspan="2">
					<textarea name="body" rows=8 wrap="soft"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Topic
				</td>
				<td colspan="2">
					<select name="topic">
						<option value='0'>(none)</option>
						<?php
						foreach(db_get_table("topics") as $item)
						{
							echo "<option value='" . $item['index'] . "'>" . $item['name'] . "</option>";
						}
						?>
					</select>
          <a href="topics.php">Modify Topics</a>
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

if (db_count("news") > 0)
{
    
  $newsArray = db_get_table("news", "1", "`timestamp` DESC", "LIMIT " . $page * NEWS_PER_PAGE . "," . NEWS_PER_PAGE);
  $topicArray = db_get_table("topics");
  
  foreach($newsArray as $item)
  {
  	echo "<div class='news_item'>";
  	echo "<div class='item_title'>";
  	if($item['title'])
  		echo "<strong>" . $item['title'] . "</strong>";
  	else
  		echo "<i><strong>no title</strong></i>";
    if($auth['loggedIn']) echo " - <a href='?act=modify&amp;page=" . intval($page + 1) . "&amp;index=" . $item['index'] . "'>Edit this entry</a>";
  	if (isset($topicArray[$item["topic"]]["name"]))
  		 echo "<br />Topic: " . $topicArray[$item["topic"]]["name"];
  	echo "<br />Posted on " . date(NEWS_DATE, $item['timestamp']);
  	echo "</div>";
    echo "<div class='item_body'>";
  	echo nl2br(bb_parse($item['body']));
  	echo "</div></div>";
  }
  
  if($numberOfNews > NEWS_PER_PAGE) //we need to paginate
  {
  	echo "<div class='pagelist'>Go to page: ";
    for($x = 1; $x <= $numberOfPages; $x++)
    {
    	if($x == $page + 1)
      	echo "<span class='pagelist_item'>" . $x . "</span>";
      else
   			echo "<a class='pagelist_item' href='index.php?page=" . $x . "'>" . $x . "</a>";
    }
    echo "</div>";
  }
}
else
{
  echo "<div class='news_item'>";
  echo "<div class='item_title'>" . NEWS_NAME . "</div>";
  echo "<div class='item_body'><em>There are no news entries to display.</em></div></div>";
}
include('../../includes/footer.php');
?>