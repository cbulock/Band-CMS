<?php
/*/////////////////////////////////////////////////
This code is copyright 2006 Michigan Web Dev and
only to be used with permisson and when licensed.
Code may be modified but may not be distributed.
Michigan Web Dev may not be liable for any loss
caused by this code and gives no warranty.
/////////////////////////////////////////////////*/
	/* ************************************************

	             AUTHENTICATION FUNCTIONS

	************************************************ */

	
	function authenticate()
	{
  	$users = db_get_table('users');
  	
  	ini_set('session.use_only_cookies',1);
		// Check for valid login - cookie or session
		if (!isset ($_SESSION)) session_start();
    $current_user = false;
		// Check if cookie data is available and valid
		if (isset($_COOKIE['mwd']))
		{
			$cookie_data = explode('@', $_COOKIE['mwd']);
			if (count($cookie_data) != 3) 
			{
				logout();
        return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid cookie data.</div>");
			}
      foreach($users as $item)
      {
      	if ($cookie_data[0] == md5($item['username']))
      	{
        	$current_user = $item;
      	}
      }
      if(!$current_user)
      {
				logout();
				return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid cookie data.</div>");
      }
			if ($cookie_data[1] != $current_user['password'])
			{
				logout();
				return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid cookie data.</div>");
			}
			if ($cookie_data[2] !=md5($_SERVER['HTTP_USER_AGENT']))
			{
				logout();
				return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid cookie data.</div>");
			}
      // Check if current session is valid
		}
		else if (isset($_SESSION['pass']) && isset($_SESSION['user']) && isset($_SESSION['agent']))
		{
      foreach($users as $item)
      {
      	if ($_SESSION['user'] == md5($item['username']))
      	{
        	$current_user = $item;
      	}
      }
      if(!$current_user)
      {
				logout();
				return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid session data.</div>");
      }
			if ($_SESSION['pass'] != $current_user['password'])
			{
				logout();
				return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid session data.</div>");
			}
			if ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))
			{
				logout();
				return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid session data.</div>");
			}
      // Check if form data is submitted  and valid
		}
		else if (isset($_REQUEST['new_user']) && isset($_REQUEST['new_password']))
		{
      foreach($users as $item)
      {
      	if ($_REQUEST['new_user'] == $item['username'])
      	{
        	$current_user = $item;
      	}
      }
      if(!$current_user)
      {
				logout();
				return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid username or password.</div>");
      }
			if (md5($_REQUEST['new_password']) != $current_user['password'])
			{
      	return array("loggedIn" => false, "host" => false, "message" => "<div class='error'>Invalid username or password.</div>");
      }
      // User passed auth, create cookie and session data.
			$_SESSION['user'] = md5($_REQUEST['new_user']);
			$_SESSION['pass'] = md5($_REQUEST['new_password']);
			$_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
			setcookie('mwd',$_SESSION['user'] . '@' . $_SESSION['pass'] . '@' . $_SESSION['agent'],time()+ SESSION_TIMEOUT, '/', '.' . DOMAIN_NAME, '0');
		}
		else
		{
			return array("loggedIn" => false, "host" => false, "message" => "");
		}
    if($current_user['type'] == "host")
    	return array("loggedIn" => true, "host" => true, "username" => $current_user['username'], "name" => $current_user['name'], "message" => "<div class='notice'>Host Logged In.</div>");
    else
    	return array("loggedIn" => true, "host" => false, "username" => $current_user['username'], "name" => $current_user['name'], "message" => "<div class='notice'>User Logged In.</div>");
	}
	
	function logout()
	{
		session_start();
		session_unset();
		session_destroy();
    setcookie('mwd', '', 1, '/', '.' . DOMAIN_NAME, '0');
	}
	
	function generatePassword($length)
	{
  	$acceptedChars = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789';
    $max = strlen($acceptedChars)-1;
    $password = null;
    for($i=0; $i < $length; $i++) {
     $password .= $acceptedChars{mt_rand(0, $max)};
    }
    return $password;
	}
	

	/* ************************************************

	              OUTPUT PARSING FUNCTIONS

	************************************************ */	
	
	function bb_parse($str)
	{
		$str = preg_replace('#\[url=(.*?)\](.*?)\[\/url\]#', '<a href="\\1" target=_blank>\\2</a>', $str);
		$str = preg_replace('#\[img\](.*?)\[\/img\]#', '<img class=postedimage src="\\1" />', $str);
		$str = preg_replace('#\[b\](.*?)\[\/b\]#', '<strong>\\1</strong>', $str);
		$str = preg_replace('#\[i\](.*?)\[\/i\]#', '<em>\\1</em>', $str);
    $str = preg_replace('#\[center\](.*?)\[\/center\]#', '<center>\\1</center>', $str);
		$str = preg_replace('#\[quote\](.*?)\[\/quote\]#s', '<div class=quote>\\1</div>', $str);
		return $str;
	}
  
  function html_parse($str)
  {
  	return preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", htmlspecialchars($str));
  }
  
  
	
	/* ************************************************

	                 CPANEL FUNCTIONS

	************************************************ */

		
	function cp_get_forwards($domain = DOMAIN_NAME)
	{
		$forwardarray = array();
		$pattern = "/\"2\">(.+?)<\/font>/";
		if ($file = @file("http://" . CP_USERNAME . ":" . CP_PASSWORD . "@" . CP_HOSTNAME . ":2082/frontend/x/mail/fwds.html"))
			 $filetext = implode("", $file);
		else
				return NULL;
		preg_match_all($pattern, $filetext, $matches);
    $found = $matches[1];
		if(count($found) > 0)
		{
			for($x = 0;$x < count($found);$x = $x + 2)
			{
        if(substr($found[$x], -strlen("@" . $domain)) == "@" . $domain)
        {
   				$forwardarray[$x]['address'] = $found[$x];
   				$forwardarray[$x]['forward'] = $found[$x + 1];
        }
			}
      if(is_null($forwardarray))
      	return false;
      else
				return $forwardarray;
		}
		else
		{
			return false;
		}
	}	

	function cp_get_default($domain = DOMAIN_NAME)
	{
		$pattern1 = "/legend><b>(.+?)<\/b>/";
		$pattern2 = "/\n(.*?)[[:space:]]{3}<\/td><\/tr><\/table>/";
		if ($file = file("http://" . CP_USERNAME . ":" . CP_PASSWORD . "@" . CP_HOSTNAME . ":2082/frontend/x/mail/def.html"))
			 $filetext = implode("", $file);
		else
				return NULL;
		preg_match_all($pattern1, $filetext, $matches1);
		preg_match_all($pattern2, $filetext, $matches2);
		
		$defaultaddress = false;
		
		foreach($matches1[1] as $key => $val)
		{
			if($val == $domain)
			{
				$defaultaddress = $matches2[1][$key];
			}
		}
		return $defaultaddress;
	}
	
	/* ************************************************

	                DATABASE FUNCTIONS

	************************************************ */
	
	
	function db_get_table($table, $where="1", $orderBy="`index` DESC", $extraSql="")
	{
		$resultArray = array();
		$sql = "SELECT * FROM `" . DB_PREFIX . $table . "` WHERE " . $where . " ORDER BY " . $orderBy . " " . $extraSql;
		$result = mysql_query($sql);
		if (!$result)
		{
			return false;
		}
		else
		{
			while ($row = mysql_fetch_array($result))
			{
				$index = $row['index'];
				foreach($row as $key => $val)
				{
					$resultArray[$index][$key] = html_parse(stripslashes($val));
				}
			}
		}
		return $resultArray;
	}
	
	function db_get_item($table, $value, $field = "index")
	{
		$resultArray = array();
		$sql = "SELECT * FROM `" . DB_PREFIX . $table . "` WHERE `" . $field . "` = '" . sqlclean($value) . "'";
		$result = mysql_query($sql);
    
		if (!$result) return false;
    $row = mysql_fetch_array($result);
    
		if (!$row) return false;
    foreach($row as $key => $val)
    {
    	$resultArray[$key] = html_parse(stripslashes($val));
    }
    return $resultArray;	
	}
	
	function db_insert($table, $data)
	{
		foreach($data as $key => $val)
		{
			$keys .= "`" . $key . "`, ";
			$vals .= "'" . sqlclean($val) . "', ";
		}
		$keys = rtrim($keys, ", ");
		$vals = rtrim($vals, ", ");
		
		$sql = "INSERT INTO `" . DB_PREFIX . $table . "` (";
		$sql .= $keys . ") VALUES (" . $vals . ")";
		if (mysql_query($sql))
		{
		 	return mysql_insert_id();
		} else {
			return FALSE;
		}
	}
	
	function db_update($table, $value, $data, $field='index')
	{
		$sql = "UPDATE `" . DB_PREFIX . $table . "` SET ";
		foreach($data as $key => $val)
		{
			$sql .= "`" . $key . "`='" . sqlclean($val) . "', ";
		}
		$sql = rtrim($sql, ", ");
		$sql .= " WHERE `" . $field . "`='" . $value . "'";
		return mysql_query($sql);
	}
	
	function db_delete($table, $value, $field='index')
	{
		$sql = "DELETE FROM `" . DB_PREFIX . $table . "` WHERE `" . $field . "` = '" . sqlclean($value) . "'";
		return mysql_query($sql);
	}	
  
  function db_count($table, $where = "1")
  {
  	$sql = "SELECT COUNT(*) FROM `" . DB_PREFIX . $table . "` WHERE " . $where;
    $result = mysql_query($sql);
    $count = (mysql_fetch_row($result));
		return $count[0];
  }
	
	function db_swap_items($table, $field, $index1, $index2)
  {
  	$item1 = db_get_item($table, $index1);
    if(!$item1) return false;
    $item2 = db_get_item($table, $index2);
    if(!$item1) return false;
  	if(!db_update($table, $index1, array($field => $item2[$field]))) return false;
  	if(!db_update($table, $index2, array($field => $item1[$field]))) return false;
    return true;
  }
  
  
  function db_delete_ordered($table, $field, $index, $where = "1")
  {
  	$item = db_get_item($table, $index);
  	if (!$item) return false;
  	
  	$sql = "UPDATE `" . DB_PREFIX . $table . "`";
  	$sql .= " SET `" . $field . "` = `" . $field . "` - 1";
  	$sql .= " WHERE `" . $field . "` > '" . $item[$field] . "'";
    $sql .= " AND " . $where;
  	$result = mysql_query($sql);
  	if (!$result)
  		return false;
  	else
  		return db_delete($table, $index);
  }
  
  function db_insert_ordered($table, $field, $data, $where = "1")
  {
  	$sql = "UPDATE `" . DB_PREFIX . $table . "`";
  	$sql .= " SET `" . $field . "` = `" . $field . "` + 1";
  	$sql .= " WHERE `" . $field . "` >= '" . $data[$field] . "'";
    $sql .= " AND " . $where;
  	$result = mysql_query($sql);
  	if (!$result)
  		return false;
  	else
  		return db_insert($table, $data);
  }
  	
	function sqlclean ($string)
	{
    if(!get_magic_quotes_gpc())
    	$string = mysql_real_escape_string($string);
    else
    	$string = addslashes($string);
    return $string;
  }
  
	/* ************************************************

	                  IMAGE FUNCTIONS

	************************************************ */	
  
  
	function resize($image, $newwidth, $newheight=NULL) { //if $newheight is set to NULL, the image with use a fixed width and the height will be variable
		$info = getimagesize($image);
    $width = $info[0];
    $height = $info[1];
  	
  	if (!$newheight) {
       $newheight = ($newwidth / $width) * $height;
  	}
  	
  	switch ($info[2]) {
    	case 1:
    		$oimage = imagecreatefromgif($image);
    		break;
    	case 2:
      	$oimage = imagecreatefromjpeg($image);
      	break;
    	case 3:
      	$oimage = imagecreatefrompng($image);
      	break;
  	}
  	$newimage = imagecreatetruecolor($newwidth, $newheight);
  	if ($info[2] == 3) imagealphablending($newimage, FALSE);
  	imagecopyresampled($newimage, $oimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
  	if ($info[2] == 3) imagesavealpha($newimage, TRUE);
  	imagedestroy($oimage);
  	return $newimage;
  }
	
	function saveImage($image, $location, $filename) {
    $result = imagejpeg($image,$location.$filename,90);
    imagedestroy($image);
		return $result;
	}

	/* ************************************************

	                FILE SYSTEM FUNCTIONS

	************************************************ */	
	function fs_list_directories($path)
  {
  	$x = 0;
    $dirnames = array();
    if ($handle = opendir($path))
    {
       while (false !== ($file = readdir($handle))) {
           if ($file != "." && $file != ".." && is_dir($path . "/" . $file))
           {
               $dirnames[$x] = $file;
               $x++;
           }
       }
       closedir($handle);
    }
    else
    {
    	return false;
    }
    return $dirnames;
  }
  
?>