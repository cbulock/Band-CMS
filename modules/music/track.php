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

$trackItem = db_get_item("tracks", $_REQUEST['track']);
$albumItem = db_get_item("albums", $trackItem['album']);

echo "<div class='music_item'>";
echo "<div class='item_title'>";
if($trackItem['name'])
  echo $trackItem['name'];
else
  echo "<i>untitled</i>";
echo " - (" . $albumItem['name'] . ", track " . $trackItem['track'] . ")";
echo "</div>";
echo "<div class='item_body'>";
echo "<table><tr><td valign=top width=" . MUSIC_ALBUM_WIDTH . ">";
echo "<img src='../../images/music/album" . $_REQUEST['album'] . "_large.jpg' /><br />";
echo "</td><td valign='top'>";

echo nl2br(bb_parse($trackItem['lyrics']));

echo "</td></tr></table><a href='index.php'>Back</a></div></div>";

include('../../includes/footer.php');

?>