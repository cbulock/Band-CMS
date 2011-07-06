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
// *** it has to happen before any output.
$page = 0;
if (isset($_REQUEST['page']))
	$page = intval($_REQUEST['page']) - 1;

$numberOfAlbums = db_count("albums");
$numberOfPages = ceil($numberOfAlbums / MUSIC_PER_PAGE);

if ($page * MUSIC_PER_PAGE > $numberOfAlbums)
	header('location: index.php');
// *** End of the first part of the pagination code

include('../../includes/header.php');

if($auth['loggedIn'])
{
	switch ($_REQUEST['act'])
	{
  	//** Album handling code
		case "postalbum":
    	$posted_date = $_REQUEST['day'] . " " . $_REQUEST['month'] . " " . $_REQUEST['year'];
      $timestamp = strtotime($posted_date);
      if($timestamp == -1)
      {
      	echo "<div class='error'>There was a problem posting your album - invalid release date.</div>";
        break;
      }
			if (!$_FILES['image']['size']) {
      	 echo "<div class='error'>You must select an image!</div>\n";
         break;
      }
			$types = array(2 => "jpg", 3 => "png");
      
      $image = $_FILES['image']['tmp_name'];
      $info = getimagesize($image);
      
      if ($info[2] <> 2 && $info[2] <> 3) { //check that uploaded file is supported
      	 echo "<div class='error'>Not a supported file or image. Image must be a JPEG or PNG.</div>\n";
      	 exit;
      }
			$addresult = db_insert("albums", array("name" => $_REQUEST['name'], "date" => $timestamp));
			if (isset($addresult)) {
				$newimg = resize($image, MUSIC_ALBUM_WIDTH);
	      saveImage($newimg,"../../images/music/","album" .$addresult . "_large.jpg");
				echo "<div class='notice'>Album successfully added.</div><br />\n";
				$numberOfAlbums = db_count("albums");
				$numberOfPages = ceil($numberOfAlbums / MUSIC_PER_PAGE);
			} else {
				echo "<div class='error'>There was a problem adding your album.</div>";
			}
		break;
		
		case "deletealbum":
			if(db_delete("albums", $_REQUEST['index'])) {
				if (!unlink("../../images/music/album" . $_REQUEST['index'] . "_large.jpg")) echo "<div class='error'>An error occured removing album art image.</div>\n";
        foreach(db_get_table("tracks", "`album` = " . $_REQUEST['index']) as $item)
        {
        	db_delete('tracks', $item['index']);
        }
				echo "<div class='notice'>Album successfully deleted.</div>";
				$numberOfAlbums = db_count("albums");
				$numberOfPages = ceil($numberOfAlbums / MUSIC_PER_PAGE);
				if ($page > $numberOfPages - 1) $page = $numberOfPages - 1;
				if ($numberOfAlbums == 0) $page = 0;
			} else {
				echo "<div class='error'>There was a problem deleting your album.</div>";
			}
		break;
		
		case "modifyalbum":
      	$posted_date = $_REQUEST['day'] . " " . $_REQUEST['month'] . " " . $_REQUEST['year'];
        $timestamp = strtotime($posted_date);
        if($timestamp == -1)
        {
        	echo "<div class='error'>There was a problem posting your album - invalid release date.</div>";
          break;
        }
			if ($_FILES['image']['size']) {
				$changeFile = TRUE;
  			$types = array(2 => "jpg", 3 => "png");
        $image = $_FILES['image']['tmp_name'];
        $info = getimagesize($image);
        if ($info[2] <> 2 && $info[2] <> 3) { //check that uploaded file is supported
        	 echo "<div class='error'>ERROR: Not a support file or image.  Image must be a JPEG or PNG.</div></div>\n";
        	 exit;
        }
			}
  		if(db_update("albums", $_REQUEST['index'], array("name" => $_REQUEST['name'], "date" => $timestamp))) {
				if ($changeFile) {
  				if (!unlink("../../images/music/album" . $_REQUEST['index'] . "_large.jpg")) {
  					 echo "<div class='error'>An error occured removing old album art image.</div>\n";
  				} else {
  					 $newimg = resize($image, MUSIC_ALBUM_WIDTH);
  					 saveImage($newimg,"../../images/music/","album" .$_REQUEST['index'] . "_large.jpg");
  				}
				}
  			echo "<div class='notice'>Album successfully modified.</div>";
  		} else {
  			echo "<div class='error'>There was a problem modifying your album.</div>";
			}
		break;
    
		//** Track handling code
		case "posttrack":
    	$trackcount = db_count("tracks", "`album` = " . $_REQUEST['album']);
      if($trackcount != 0)
      {
      	switch($_REQUEST['trackoption'])
        {
        	case "first":
          	$selectedtrack = 1;
          break;
          case "last":
          	$selectedtrack = $trackcount + 1;
          break;
          case "after":
          	$selectedtrack = $_REQUEST['aftertrack'] + 1;
            if($selectedtrack > $trackcount + 1) $selectedtrack = $trackcount + 1;
          break;
        }
      }
      else
      {
      	$selectedtrack = 1;
      }
			if(db_insert_ordered("tracks", "track", array("name" => $_REQUEST['name'], "album" => $_REQUEST['album'], "track" => $selectedtrack, "lyrics" => $_REQUEST['lyrics']), "`album` = " . $_REQUEST['album']))
				echo "<div class='notice'>Track successfully added.</div>";
			else
				echo "<div class='error'>There was a problem adding your track.</div>";
		break;
		
		case "deletetrack":
    	$thistrack = db_get_item("tracks", $_REQUEST['index']);
			if(db_delete_ordered("tracks", "track", $_REQUEST['index'], "`album` = " . $thistrack['album']))
				echo "<div class='notice'>Track successfully deleted.</div>";
			else
				echo "<div class='error'>There was a problem deleting your track.</div>";
		break;
		
		case "modifytrack":
  		if(db_update("tracks", $_REQUEST['index'], array("name" => $_REQUEST['name'], "lyrics" => $_REQUEST['lyrics'])))
  			echo "<div class='notice'>Track successfully modified.</div>";
  		else
  			echo "<div class='error'>There was a problem modifying your track.</div>";
		break;
	}
  ?>
  <div class="admin_item">
    <div class='item_title'>Add a new album:</div>
    <div class="item_body">
    <form name="modify" action="index.php?act=postalbum&amp;page=<?php echo $page + 1; ?>" method="post" enctype="multipart/form-data">
      <table>
        <tr>
          <td>
          	Name
          </td>
          <td>
          	<input size="50" type="text" name="name" value="">
          </td>
        </tr>
  			<?php
        	$timestamp = time();
        	include('when.php');
        ?>
				<tr>
          <td>
          	Album Art
          </td>
          <td>
          	<input size="50" type='file' name='image' />
          </td>
        </tr>
        <tr>
          <td>
          </td>
          <td>
            <br />
            <input type="hidden" name="index" value="<?php echo $albumindex; ?>">
            <input type="submit" name="submit" value="Add this item">
          </td>
        </tr>
      </table>
    </form>
    <br />
  </div>
  </div>
  <?php
}


if (db_count("albums") > 0)
{
  $musicArray = db_get_table("albums", "1", "`date` DESC", "LIMIT " . $page * MUSIC_PER_PAGE . "," . MUSIC_PER_PAGE);
  
  foreach($musicArray as $item)
  {
  	echo "<div class='music_item'>";
  	echo "<div class='item_title'>";
  	if($item['name'])
  		echo $item['name'];
  	else
  		echo "<i>untitled</i>";
  	echo " - released " . date(MUSIC_DATE, $item['date']);
    if($auth['loggedIn']) echo " - <a href='modify.php?act=modifyalbum&amp;page=" . intval($page + 1) . "&amp;index=" . $item['index'] . "'>Modify Album</a> - <a href='modify.php?act=posttrack&amp;page=" . intval($page + 1) . "&amp;index=" . $item['index'] . "'>Add Track</a>";
  	echo "</div>";
    echo "<div class='item_body'>";
  	echo "<table><tr><td width=" . MUSIC_ALBUM_WIDTH . ">\n";
    echo "<img src='../../images/music/album" . $item['index'] . "_large.jpg' />\n";
    echo "</td><td valign='top'>";
    $tracks = db_get_table("tracks", "`album` = " . $item['index'], "`track` ASC");
    if(!$tracks)
    {
    	echo "<i>No tracks</i>";
    }
    else
    {
    	foreach($tracks as $track)
      {
      	$linka = ""; $linkb = "";
  			if ($track['lyrics']) {
    		 $linka = "<a href='track.php?track=" . $track['index'] . "&amp;album=" . $item['index'] . "'>";
    		 $linkb = "</a>";
  			}
        if($auth['loggedIn']) echo "(<a href='modify.php?act=modifytrack&amp;page=" . intval($page + 1) . "&amp;index=" . $track['index'] . "'>Edit</a>) - \n";
  			echo $track['track'] . " - " . $linka . $track['name'] . $linkb . "\n";
        echo "<br />";
      }
    }
  	echo "</td></tr></table></div></div>";
  }
  
  if($numberOfAlbums > MUSIC_PER_PAGE) //we need to paginate
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
  echo "<div class='music_item'>";
  echo "<div class='item_title'>" . MUSIC_NAME . "</div>";
  echo "<div class='item_body'><em>There are no albums to display.</em></div></div>";
}

include('../../includes/footer.php');

?>