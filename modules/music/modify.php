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

$page = 0;
if (isset($_REQUEST['page']))
	$page = intval($_REQUEST['page']) - 1;

include('../../includes/header.php');

if($auth['loggedIn'])
{
	switch ($act)
	{
  	//** Album handling code
		case "postalbum":
    	echo "Adding album... ";
		break;
		
		case "modifyalbum":
  		$albumindex = $_REQUEST['index'];
  		$albumitem = db_get_item("albums", $albumindex);
  		?>
  		<div class="admin_item">
  			<div class='item_title'>Modify this album:</div>
        <div class='item_body'>
  			<form name="modify" action="index.php?act=modifyalbum&amp;page=<?php echo $page + 1; ?>" method="post" enctype="multipart/form-data">
  			<table>
  				<tr>
  					<td>
  						Name
  					</td>
  					<td>
  						<input size="50" type="text" name="name" value="<?php echo $albumitem['name']; ?>">
  					</td>
  				</tr>
            <?php
            	$timestamp = $albumitem['date'];
            	include('when.php');
            ?>
					<tr>
  					<td>
  						Album Art
  					</td>
  					<td>
  						<img src='../../images/music/album<?php echo $albumindex; ?>_large.jpg' />
  					</td>
  				</tr>
					<tr>
  					<td>
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
              	<a href="index.php?act=deletealbum&amp;page=<?php echo $page + 1; ?>&amp;index=<?php echo $albumindex; ?>">Delete this item</a>
              </td>
            </tr>
          </table>
  		</div>
      </div>
  		<?php
		break;
    
		//** Track handling code
		case "posttrack":
    	$albumindex = $_REQUEST['index'];
  		?>
  		<div class="admin_item">
  			<div class='item_title'>Add a new track:</div>
        <div class='item_body'>
  			<form name="modify" action="index.php?act=posttrack&amp;page=<?php echo $page + 1; ?>" method="post">
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
          $trackcount = db_count("tracks", "`album` = " . $albumindex);
          if($trackcount != 0)
          {
          ?>
  				<tr>
  					<td>
  						Add this track...
  					</td>
  					<td>
  						<?php
                if($trackcount > 1)
                {
                  ?>
                  <input type="radio" name="trackoption" value="first">as the first track<br />
    							<input type="radio" name="trackoption" value="last" checked>as the last track<br>
    							<input type="radio" name="trackoption" value="after">after track 
                  <?php
                  echo "<select name='aftertrack'>";
                    for($x = 1; $x <= $trackcount; $x++)
                    {
                  		echo "<option value='" . $x . "'>" . $x . "</option>";
                    }
                  echo "</select>";
                }
                else
                {
                ?>
                  <input type="radio" name="trackoption" value="first">as the first track<br />
    							<input type="radio" name="trackoption" value="last" checked>as the last track<br>
                <?php
                }
                ?>
  					</td>
  				</tr>
          <?php
          }
          ?>
					<tr>
  					<td>
  						Lyrics
  					</td>
  					<td>
  						<textarea name="lyrics" rows=8 wrap="soft"></textarea>
  					</td>
  				</tr>
  				<tr>
  					<td>
  					</td>
  					<td>
  						<br />
              <input type="hidden" name="album" value="<?php echo $albumindex; ?>">
  						<input type="submit" name="submit" value="Add this track">
  						</form>
  					</td>
  				</tr>
  			</table>
          <br />
         	<a href="index.php?page=<?php echo $page + 1; ?>">Cancel</a>
  		</div>
      </div>
  		<?php
		break;
		
		case "modifytrack":
  		$trackindex = $_REQUEST['index'];
  		$trackitem = db_get_item("tracks", $trackindex);
  		?>
  		<div class="admin_item">
  			<div class='item_title'>Modify this track:</div>
        <div class='item_body'>
  			<form name="modify" action="index.php?act=modifytrack&amp;page=<?php echo $page + 1; ?>" method="post">
  			<table>
  				<tr>
  					<td>
  						Name
  					</td>
  					<td>
  						<input size="50" type="text" name="name" value="<?php echo $trackitem['name']; ?>">
  					</td>
  				</tr>
					<tr>
  					<td>
  						Lyrics
  					</td>
  					<td>
  						<textarea name="lyrics" rows=8 wrap="soft"><?php echo $trackitem['lyrics']; ?></textarea>
  					</td>
  				</tr>
  				<tr>
  					<td>
  					</td>
  					<td>
  						<br />
              <input type="hidden" name="index" value="<?php echo $trackindex; ?>">
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
              	<a href="index.php?act=deletetrack&amp;page=<?php echo $page + 1; ?>&amp;index=<?php echo $trackindex; ?>">Delete this item</a>
              </td>
            </tr>
          </table>
  		</div>
      </div>
  		<?php
		break;
	}	
}

include('../../includes/footer.php');

?>