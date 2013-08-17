<?php 
include 'requestHandler.php';
//include 'functions.php' 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Engine Number 9</title>
	</head>
	<body>
		<h1>Hello</h1>
		<?php
		$stops = request_API("/location/rest/stops?ids=000043");
		 ?>
	</body>
</html>
