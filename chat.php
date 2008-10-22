<?
include("../session_mysql.php");
include("functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="<?=$path;?>/css/stylesheet.css.php" type="text/css" media="screen" charset="utf-8">
</head>
<body id="clean">
	<div id="chat" class="mini">
		<?=printChat();?>
	</div>
</body>
</html>