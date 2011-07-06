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

$item = db_get_item("events", $_REQUEST['index']);

echo "<div class='event_item'>";
echo "<div class='item_title'>";
if($item['name'])
  echo $item['name'];
else
  echo "Event";
if($auth['loggedIn']) echo " - <a href='?act=modify&index=" . $item['index'] . "'>Modify</a>";
echo "</div>";
echo "<div class='item_body'>";
echo "<table>";
echo "<tr><td>Date</td><td>Time</td><td>Name</td><td>Venue</td><td>Where</td></tr>";
echo "<tr><td class='eventable' align='left'>";
echo date("D, M jS, Y", $item['when']);
echo "</td><td class='eventable' align='left'>";
echo date("g:i a", $item['when']);
echo "</td><td class='eventable' align='left'>";
echo $item['name'];
echo "</td><td class='eventable' align='left'>";
echo $item['venue'];
echo "</td><td class='eventable' align='left'>";
echo $item['where'];
echo "</td></tr></table><br />";

echo $item['description'];

echo "<br /><br /><a href='index.php'>Back</a>";
echo "</div></div>";

include('../../includes/footer.php');

?>