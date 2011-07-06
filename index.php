<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Content-type: text/html; charset=utf-8");
  
	include_once('includes/config.php');
  $auth = authenticate();
	if ($_REQUEST['frame'])
		$iframepage = $_REQUEST['frame'];
	else
		$iframepage = STARTING_PAGE;
    
	$style_folder = DOMAIN_NAME . SCRIPT_FOLDER . "/styles";
  if(STYLESHEET_FOLDER != "")
  	$style_folder .= "/" . STYLESHEET_FOLDER;
    
  $act = false;
  if(isset($_REQUEST['act'])) $act = $_REQUEST['act'];
  switch($act)
  {
  	case "changestyle":
    	if($auth['host'])
      {
      	$data = array("value" => $_REQUEST['stylesheet']);
        db_update("config", "STYLESHEET_FOLDER", $data, "name");
        header('location: index.php');
      }
    break;
  }

?>

<html>
	<head>
		<title>
			<?php echo SITE_NAME; ?>
		</title>
		<link href="http://<?php echo $style_folder; ?>/index.css?<?php echo time(); ?>" type="text/css" rel="stylesheet">
	</head>
	<body>
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top">
					<table id="maintable" name="maintable" height="100%" align="center" CELLPADDING=0 CELLSPACING=0>
						<tr>
							<td name="header" id="header">
              	&nbsp;
                <?php
                	if($auth['host'])
                  {
                  	$foldernames = fs_list_directories("styles");
                    if($foldernames != false)
                    {
                    	echo "<form name='modify' action='?act=changestyle' method='post'>";
                    	echo "<select name='stylesheet'>";
                      echo "<option value=''>(default)</option>";
                      foreach($foldernames as $val)
                      {
                      	echo "<option value='" . $val . "'";
                        if($val == STYLESHEET_FOLDER) echo " selected";
                        echo ">" . $val . "</option>";
                      }
                      echo "</select>";
                      echo "<input type='submit' name='submit' value='Change Style'>";
                      echo "</form>";
                    }
                  }
                ?>
							</td>
						</tr>
          	<tr>
          		<td name="sitemenu" id="sitemenu">
              	<?php
                
          			echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/news/' target='site_body'>" . NEWS_NAME . "</a>" . MENU_SEPERATOR;
                echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/events/' target='site_body'>" . EVENTS_NAME . "</a>" . MENU_SEPERATOR;
                echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/music/' target='site_body'>" . MUSIC_NAME . "</a>" . MENU_SEPERATOR;
                echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/links/' target='site_body'>" . LINKS_NAME . "</a>" . MENU_SEPERATOR;
                echo "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/modules/gallery/' target='site_body'>" . GALLERY_NAME . "</a>";
								
								$pagesArray = db_get_table("pages", "1", "`index` ASC");
								foreach ($pagesArray as $item) 
								{
								 	if (!$item['hide']) {
										 if ($item['type'] == 'text')
										 		echo MENU_SEPERATOR . "<a href='http://" . DOMAIN_NAME . SCRIPT_FOLDER . "/pages/" . $item['menu_title'] . "' target='site_body'>" . $item['menu_title'] . "</a>";
										 else
										 		echo MENU_SEPERATOR . "<a href='" . $item['url'] . "' target='site_body'>" . $item['menu_title'] . "</a>";
									}
								}
                ?>
              </td>
            </tr>
    				<tr>
							<td name="sitebody" id="sitebody" valign="top">
								<iframe width="100%" frameborder=0 height="100%" name="site_body" id="site_body" src="http://<?php echo DOMAIN_NAME . SCRIPT_FOLDER . '/' . $iframepage; ?>"></iframe>
							</td>
						</tr>
						<tr>
							<td name="footer" id="footer">
		          	All content  &#0169; <?php echo date('Y') . " " . SITE_NAME; ?>, except where specified. <?php echo bb_parse(FOOTER_TEXT); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>