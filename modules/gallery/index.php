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

$numberOfImages = db_count("gallery");
$galleryPerPage = GALLERY_THUMBNAIL_ROWS * GALLERY_THUMBNAIL_COLS;
$numberOfPages = ceil($numberOfImages / $galleryPerPage);

if ($page * $galleryPerPage > $numberOfImages)
	header('location: index.php');
// *** End of the first part of the pagination code

include('../../includes/header.php');

$galleryArray = db_get_table("gallery", "1", "`index` DESC", "LIMIT " . $page * $galleryPerPage . "," . $galleryPerPage);
if ($galleryArray) {
  foreach ($galleryArray as $item) {
  	$height[] = $item['height'];
  }
  $largestImgHeight = max($height);
} else {
  $largestImgHeight = 0;
}
?>
<script type='text/javascript' src='images.js'></script>
<script type='text/javascript'>
<?php
foreach($galleryArray as $item) {
	echo "preload('image".$item['index']."','../../images/gallery/".$item['filename']."');\n";
}
?>
</script>

<?php
if($auth['loggedIn'])
{
	switch ($_REQUEST['act'])
  	{
  	case "delete":
    	preg_match_all("/(.*)\/(.*)/s",$_GET['img'],$results);
    	$lastresult = end($results);
    	$file = $lastresult[0];
  		if (!unlink("../../images/gallery/thumbs/".$file)) echo "<div class='error'>An error occured removing thumbnail image.</div>\n";
  		if (!unlink("../../images/gallery/".$file)) echo "<div class='error'>An error occured removing image.</div>\n";
  		if (db_delete('gallery',$file,'filename')) {
  			echo "<div class='notice'>Image Removed from Gallery.</div>\n";
  		} else {
  			echo "<div class='error'>An error occured removing image from database.</div>\n";
  		}
			$numberOfImages = db_count("gallery");
      $numberOfPages = ceil($numberOfImages / $galleryPerPage);
			if ($page > $numberOfPages - 1) $page = $numberOfPages - 1;
			$galleryArray = NULL;
			$galleryArray = db_get_table("gallery", "1", "`index` DESC", "LIMIT " . $page * $galleryPerPage . "," . $galleryPerPage);
      if ($galleryArray) {
        foreach ($galleryArray as $item) {
        	$height[] = $item['height'];
        }
        $largestImgHeight = max($height);
      } else {
        $largestImgHeight = 0;
      }
  	break;
  	case "add":
      $now = date(dmYHis);
      if (!$_FILES['image']['size']) {
      	 echo "<div class='error'>You must select an image!</div>\n";
         break;
      }
      		
      $types = array(2 => "jpg", 3 => "png");
      
      $image = $_FILES['image']['tmp_name'];
      $info = getimagesize($image);
      
      if ($info[2] <> 2 && $info[2] <> 3) { //check that uploaded file is supported
      	 echo "<div class='error'>Not a supported file or image.  Image must be a JPEG or PNG.</div>\n";
      	 break;
      }
      
      $thumb = resize($image, GALLERY_THUMBNAIL_WIDTH, GALLERY_THUMBNAIL_HEIGHT);
      saveImage($thumb,"../../images/gallery/thumbs/",$now.".jpg");
      
      $fullimg = resize($image, GALLERY_IMAGE_WIDTH);
      saveImage($fullimg,"../../images/gallery/",$now.".jpg");
  		$newinfo = getimagesize("../../images/gallery/".$now.".jpg");
      
      $data = array (
      'filename' => $now.".jpg",
      'height' => $newinfo[1]
      );
      
      if (db_insert("gallery",$data)) echo "<div class='notice'>Image added to Gallery.</div>\n";
			$numberOfImages = db_count("gallery");
			$numberOfPages = ceil($numberOfImages / $galleryPerPage);
			$galleryArray = db_get_table("gallery", "1", "`index` DESC", "LIMIT " . $page * $galleryPerPage . "," . $galleryPerPage);
      if ($galleryArray) {
        foreach ($galleryArray as $item) {
        	$height[] = $item['height'];
        }
        $largestImgHeight = max($height);
      } else {
        $largestImgHeight = 0;
      }
			reset($galleryArray);
			$firstItem = current($galleryArray);
			echo "<script type='text/javascript'>";
      echo "preload('image".$firstItem['index']."','../../images/gallery/".$firstItem['filename']."');\n";
      echo "</script>";
  	break;
  }//end of switch
  ?>
  <div class='admin_item'>
  <script type='text/javascript'>
  function removeImage(ImageSrc,Page) {
  window.location="index.php?act=delete&page="+Page+"&img="+ImageSrc;
  }
  </script>
  <div class="item_title">Upload New Photo:</div>
  <div class='item_body'>
	<form action='?act=add' method='post' enctype="multipart/form-data">
	<input type='file' name='image' />
	<input type='submit' value='Submit' />
	</div></div>
<?php
} //end of if($loggedIn)
?>
<div class="gallery_item">
<div class="item_title">
 	Photo Gallery
</div>
<div class='item_body'>
<table width='100%'>
		<tr>
  		<td valign='top'>
				<?php
				$colcount=0;
				if(!$galleryArray)
        {
        	echo "<em>There are no images to display.</em>";
        }
        else
        {
  				foreach($galleryArray as $i => $item) {
    				$colcount++;
    				echo "<a href=\"javascript:changeImage('gallery_item','fullImg','image".$i."')\"><img src='../../images/gallery/thumbs/".$item['filename']."' /></a> \n";
    				if ($colcount == GALLERY_THUMBNAIL_COLS){
      				echo "<br />\n";
      				$colcount=0;
    				}
  				}
				}
				if($numberOfImages > $galleryPerPage) //we need to paginate
          {
          	echo "<div class='pagelist'>Go to page: ";
          	
            for($x = 1; $x <= $numberOfPages; $x++)
            {
            	if($x == $page + 1)
              	echo "<span class='pagelist_item'>" . $x . "</span>";
              else
           			echo "<a class='pagelist_item' href='" . $_SERVER['PHP_SELF'] . "?page=" . $x . "'>" . $x . "</a>";
            }
            echo "</div>";
        }
			if ($auth['loggedIn'] && $galleryArray)
				echo "<br /><a href=\"javascript:removeImage(document.images.fullImg.src,". intval($page+1) .")\">Delete this photo</a>\n";
			if ($galleryArray) {
  			reset($galleryArray);
  			$firstItem = current($galleryArray);
			}
				?>
  		</td>
  		<td>
			<div id='fullImgWrap' style='height:<?php echo $largestImgHeight; ?>px;'>
				<?php if ($galleryArray) { ?>
    		<img src='../../images/gallery/<?php echo $firstItem['filename']; ?>' name='fullImg' />
				<?php } ?>
			</div>
  		</td>
	</tr>
</table>
</div>
</div>
<?php
include('../../includes/footer.php');
?>