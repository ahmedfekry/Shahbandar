<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
HI
<?php
$cookie_name = "user";
$cookie_value = "John Doe";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
?>
</body>
</html>