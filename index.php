<?php include_once("config-admin.php");?>
<?php
	$title = "HOME-ANDROID";
	$r = false;
	if(isset($_REQUEST['action'])){
		$url = sqi($_REQUEST['action']);
		$r = getrecord("menu", "url = '$url'"); 
		if(count($r) > 0) {
			$title = ' | '.$r[0]['name'];
		}
	}
	include("include/header.php");
	if(isset($_REQUEST['action'])){
		$url = sqi($_REQUEST['action']);
		$r = getrecord("menu", "url = '$url'"); 
		if(count($r) > 0) {
			$title = ' | '.$r[0]['name'];
		}
		if($r != false){
			$file ='include/'.$r[0]['url'].'.php';
			if(is_file($file)){
				echo('<div id="body">');
					echo('<div class= "titlebody">'.$r[0]['name'].'</div>');
					echo('<div class="panel-main">');
					include_once($file);
					echo('</div>');
				echo('</div>');
					
			} else {
				include_once("include/500.php");
			}
		} else {
			include_once("include/404.php");
		}
	} else {
		include_once("include/index.php");
	}
	include_once("include/footer.php");


?>