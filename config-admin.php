<?php

// Pay attention that we shouldn't print anything in config file
//it's used to be able to use a same session everywhere
@session_start();

define('URL', 'http://localhost/digikala/admin/');
define("DB_LOCAL", "localhost");
define("DB_NAME", "digikala");
define("DB_USER", "root");
define("DB_PASS", "");

function db_connect(){
	/*
	-- the '@' before mysqi_connect() causes to prevent from displaying the ugly orange tables when we have problems with connecting to the database
	
	-- the die() method executes when the connection fails and prevents from progressing and facing with future bugs pertaining to this connection problem
	*/
	$link = @mysqli_connect(DB_LOCAL, DB_USER, DB_PASS, DB_NAME) or die(exit("connection failed!")); 
	if($link){
		//for displaying appropriate persian characters
		mysqli_query($link, "SET NAME UTF8");
		mysqli_query($link, "SET CHARACTER SET UTF8");
		mysqli_query($link, "SET character _set_connection = 'utf8'");
		return $link;
	}else{
		die(exit("connection failed"));
		return false;
	}
}

function getrecord($tblname, $where = 1){
	$link = db_connect();
	$tblname = sqi($tblname);
	$query = "select * from $tblname where $where";
	$r = mysqli_query($link, $query);
	if($r){
		$res = array();
		$i = 0;
		while($row = mysqli_fetch_assoc($r)){
			$res[$i] = $row;
			$i++;
		}
	return $res;
	} else {
//		echo mysqli_error($link);
		return false;
	}
	
}

function runsql($query){
	$link = db_connect();
	$r = mysqli_query($link, $query);
	if($r){
		$res = array();
		$i = 0;
		while($row = mysqli_fetch_assoc($r)){
			$res[$i] = $row;
			$i++;
		}
	return $res;
	} else {
//		echo mysqli_error($link);
		return false;
	}
	
}

//var_dump(getrecord("admins"));
//var_dump(getrecord("admins","username = 'admin' and password = 'e10adc3949ba59abbe56e057f20f883e'"));

//for preventing from hack and for performing CRUD operations we shoud use some rules that mySQLi supports them
function sqi($value){
	$link = db_connect();
	if(get_magic_quotes_gpc()){
		$value = stripslashes($value);
	}
	//use the below function to standardize the value based on mySQLi rules
	if(function_exists("mysqli_real_escape_string")){
		$value = mysqli_real_escape_string($link, $value);
	} else{
		$value = addslashes($value);
	}
	return $value;
}

function addrecord($tblname, $values = null){
	$tblname = sqi($tblname);
	$link = db_connect();
	if(is_array($values)){
		$key = array_keys($values);
		$values = array_values($values);
		$i = 0;
		/*
		foreach($values as $row): each time it assingns the value of $values in $row
		    means ----------> $row = $values
		*/
		foreach($values as $row){
			// the "'" puts the values between two '
			$value[$i] = "'".sqi($row)."'";
			$i++;
		}
//		var_dump($value);
		/*
			separate the key and value amounts with ','
			use implode() func to only return the values of each array
			-> if we show the array without that function it will show sth like below:
				array (size=2)
  				0 => string ''home'' (length=6)
  				1 => string ''android'' (length=9)
				(index) => (amount of index)
		*/
		$key = implode(',', $key);
		// var_dump($key); =>  {'name, family'}
		$value = implode(',', $value);
		$query = "INSERT INTO `$tblname` ($key) VALUES ($value)";
//		INSERT INTO `tbltest` (`idtest`, `name`, `family`) VALUES (NULL, 'home', 'android');
//		echo $query;
		
		$r = mysqli_query($link, $query);
		if($r){
			return true;
		}else{
			echo mysqli_error($link);
			return false;
		}
	}
}

// var_dump(addrecord("tbltest", array("name" => "lllllll", "family" => "mmmmm")));

function updaterecord($tblname, $values, $where){
	$tblname = sqi($tblname);
	$link = db_connect();
	if(is_array($values)){
		$key = array_keys($values);
		$values = array_values($values);
		$i = 0;
		/*
		foreach($values as $row): each time it assingns the value of $values in $row
		    means ----------> $row = $values
		*/
		foreach($values as $row){
			// the "'" puts the values between two '
			$value[$i] = "`$key[$i]` = '".sqi($row)."'";
			$i++;
		}
		
		$key = implode(',', $key);
		// var_dump($key); =>  {'name, family'}
		$value = implode(',', $value);
		$query = "UPDATE `$tblname` SET $value WHERE $where";
		$r = mysqli_query($link, $query);
		if($r){
			return true;
		} else {
			return false;
		}
		return false;
	}
}

function delete_record($tblname, $where){
	$link = db_connect();
	$tblname = sqi($tblname);
	$query = "DELETE FROM `$tblname` WHERE $where";
		$r = mysqli_query($link, $query);
		if($r){
			return true;
		} else {
			return false;
		}
		return false;
}




//echo delete_record("tbltest", "family = android");							
?>


<?php 
	if(!isset($login)){
		if(!isset($_SESSION['admin_id'])){
			header('location: '.URL.'login.php');
			exit();
		} else {
			if(isset($_SESSION['admin_id'])){
				$idadmin = $_SESSION['admin_id'];
			}
		}
	}
?>