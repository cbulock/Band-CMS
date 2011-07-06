<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
include('../includes/config.php');

$item = db_get_item('config',$_REQUEST['index']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<title>Config Description</title>
<style>
body {
font-family:Helvetica,Arial,sans-serif;
}
</style>
</head>
<body>

<h4><?php echo $item['pretty_name'];?></h4>
<hr>
<p>
<?php echo nl2br(bb_parse($item['description']));?>
</p>
<input type='button' value='Close' onclick='javascript:self.close()'>
</body>
</html>