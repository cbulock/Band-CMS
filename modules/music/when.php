<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
	$eventDate['day'] = date("d", $timestamp);
  $eventDate['month'] = date("M", $timestamp);
  $eventDate['year'] = date("Y", $timestamp);
  $eventDate['hour'] = date("g", $timestamp);
  $eventDate['minute'] = date("i", $timestamp);
  $eventDate['meridiem'] = date("a", $timestamp);
?>
  		<tr>
  			<td>
  				Release Date
  			</td>
  			<td>
  				<select name="day">
          	<?php
            for($x = 1; $x <= 31; $x++)
            {
            	echo "<option value='" . $x . "'";
              if($eventDate['day'] == $x) echo " selected";
              echo ">" . $x . "</option>";
            }            	
            ?>
  				</select>
  				<select name="month">
            <option value='Jan'<?php if($eventDate['month'] == "Jan") echo " selected"; ?>>Jan</option>
            <option value='Feb'<?php if($eventDate['month'] == "Feb") echo " selected"; ?>>Feb</option>
            <option value='Mar'<?php if($eventDate['month'] == "Mar") echo " selected"; ?>>Mar</option>
            <option value='Apr'<?php if($eventDate['month'] == "Apr") echo " selected"; ?>>Apr</option>
            <option value='May'<?php if($eventDate['month'] == "May") echo " selected"; ?>>May</option>
            <option value='Jun'<?php if($eventDate['month'] == "Jun") echo " selected"; ?>>Jun</option>
            <option value='Jul'<?php if($eventDate['month'] == "Jul") echo " selected"; ?>>Jul</option>
            <option value='Aug'<?php if($eventDate['month'] == "Aug") echo " selected"; ?>>Aug</option>
            <option value='Sep'<?php if($eventDate['month'] == "Sep") echo " selected"; ?>>Sep</option>
            <option value='Oct'<?php if($eventDate['month'] == "Oct") echo " selected"; ?>>Oct</option>
            <option value='Nov'<?php if($eventDate['month'] == "Nov") echo " selected"; ?>>Nov</option>
            <option value='Dec'<?php if($eventDate['month'] == "Dec") echo " selected"; ?>>Dec</option>
  				</select>
  				<select name="year">
          	<?php
            for($x = 2000; $x <= 2020; $x++)
            {
            	echo "<option value='" . $x . "'";
              if($eventDate['year'] == $x) echo " selected";
              echo ">" . $x . "</option>";
            }            	
            ?>
  				</select>
  			</td>
      </tr>