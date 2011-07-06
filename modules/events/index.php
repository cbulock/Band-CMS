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
    	$posted_date = $_REQUEST['day'] . " " . $_REQUEST['month'] . " " . $_REQUEST['year'] . " " . $_REQUEST['hour'] . ":" .  $_REQUEST['minute'] . " " . $_REQUEST['meridiem'];
      $timestamp = strtotime($posted_date);
      if($timestamp == -1)
      {
      	echo "<div class='error'>There was a problem posting your event - invalid date / time.</div>";
        break;
      }
			if(db_insert("events", array("name" => $_REQUEST['name'], "venue" => $_REQUEST['venue'], "where" => $_REQUEST['where'], "when" => $timestamp, "description" => $_REQUEST['description'])))
				echo "<div class='notice'>Event successfully posted!</div>";
			else
				echo "<div class='error'>There was a problem posting your event.</div>";
		break;
		
		case "delete":
			if(db_delete("events", $_REQUEST['index']))
				echo "<div class='notice'>Event successfully deleted!</div>";
			else
				echo "<div class='error'>There was a problem deleting your event.</div>";
		break;
		
		case "modify":
			if(isset($_REQUEST['name']))
			{
      	$posted_date = $_REQUEST['day'] . " " . $_REQUEST['month'] . " " . $_REQUEST['year'] . " " . $_REQUEST['hour'] . ":" .  $_REQUEST['minute'] . " " . $_REQUEST['meridiem'];
        $timestamp = strtotime($posted_date);
        if($timestamp == -1)
        {
        	echo "<div class='error'>There was a problem modifying your event - invalid date / time.</div>";
          break;
        }
				if(db_update("events", $_REQUEST['index'], array("name" => $_REQUEST['name'], "venue" => $_REQUEST['venue'], "where" => $_REQUEST['where'], "when" => $timestamp, "description" => $_REQUEST['description'])))
					echo "<div class='notice'>Event successfully modified!</div>";
				else
					echo "<div class='error'>There was a problem modifying your event.</div>";
			}
			else
			{
				$eventindex = $_REQUEST['index'];
				$eventitem = db_get_item("events", $eventindex);
				?>
				<div class="admin_item">
					<div class='item_title'>Modify this event:</div>
          <div class='item_body'>
  					<form name="modify" action="?act=modify&amp;page=<?php echo $page + 1; ?>" method="post">
  					<table>
  						<tr>
  							<td>
  								Name
  							</td>
  							<td>
  								<input size="50" type="text" name="name" value="<?php echo $eventitem['name']; ?>">
  							</td>
  						</tr>
            	<tr>
            		<td>
            			Venue
            		</td>
            		<td>
            			<input colspan="3" size="50" type="text" name="venue" value="<?php echo $eventitem['venue']; ?>">
            		</td>
            	</tr>
  						<tr>
  							<td>
  								Where
  							</td>
  							<td>
  								<input size="50" type="text" name="where" value="<?php echo $eventitem['where']; ?>">
  							</td>
  						</tr>
              <?php
              	$timestamp = $eventitem['when'];
              	include('when.php');
              ?>
  						<tr>
  							<td>
  								Description
  							</td>
  							<td>
  								<textarea name="description" rows=8 wrap="soft"><?php echo $eventitem['description']; ?></textarea>
  							</td>
  						</tr>
  						<tr>
  							<td>
  							</td>
  							<td>
  								<br />
  								<input type="hidden" name="index" value="<?php echo $eventindex; ?>">
  								<input type="submit" name="submit" value="Update this event">
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
                	<a href="index.php?act=delete&amp;page=<?php echo $page + 1; ?>&amp;index=<?php echo $eventindex; ?>">Delete this event</a>
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
		<div class="item_title">Post a new event:</div>
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
  					Venue
  				</td>
  				<td>
  					<input colspan="3" size="50" type="text" name="venue" value="">
  				</td>
  			</tr>
  			<tr>
  				<td>
  					Where
  				</td>
  				<td>
  					<input colspan="3" size="50" type="text" name="where" value="">
  				</td>
  			</tr>
  			<?php
        	$timestamp = time();
        	include('when.php');
        ?>
  			<tr>
  				<td>
  					Description
  				</td>
  				<td colspan="2">
  					<textarea name="description" rows=8 wrap="soft"></textarea>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="2" align="right">
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

echo "<div class='event_item'>";
echo "<div class='item_title'>" . EVENTS_NAME . "</div><div class='item_body'>";

if (db_count("events") > 0)
{
  $eventsArray = db_get_table("events", "1", "`when` DESC");
    
  if($eventsArray)
  {
  	$toggle = true;
    echo "<table>";
    echo "<tr><td>Date</td><td>Time</td><td>Name</td><td>Venue</td><td>Where</td></tr>";
    foreach($eventsArray as $item)
    {
    	if($toggle)
    		$class = "eventable";
    	else
    		$class = "oddtable";
    	echo "<tr><td class='" . $class . "' align='left'>";
    	echo date(EVENTS_DATE, $item['when']);
    	echo "</td><td class='" . $class . "' align='left'>";
      echo date(EVENTS_TIME, $item['when']);
  		echo "</td><td class='" . $class . "' align='left'>";
      echo $item['name'];
  		echo "</td><td class='" . $class . "' align='left'>";
      if($item['venue'])
      	$venuename = $item['venue'];
     	else
      	$venuename = "<i>not specified</i>";
  		if ($item['description'])
  			echo "<a href='event.php?index=" . $item['index'] . "'>" . $venuename . "</a>";
      else
      	echo $venuename;
  		echo "</td><td class='" . $class . "' align='left'>";
      echo $item['where'];
      if($auth['loggedIn']) echo "</td><td align='center'><a href='?act=modify&index=" . $item['index'] . "'>Modify</a></td>";
      echo "</tr>";
      $toggle = !$toggle;
    }
    echo "</table>";
  }
}
else
{
  echo "<em>There are no events to display.</em>";
}

echo "</div></div>";

include('../../includes/footer.php');
?>