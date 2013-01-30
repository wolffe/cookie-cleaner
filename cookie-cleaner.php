<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Cookie Cleaner</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width">
</head>
<body>

<?php
error_reporting(E_ALL);

$cookie_domain = '.domain.ext'; // your cookie domain (e.g. .google.com)
?>

<h1>Cookie Cleaner</h1>
<p>Reading <b>php.ini</b> values...</p>
<?php
echo '<ul>';
	echo '<li><b>php.ini</b> value for <em>session.cookie_lifetime</em> is: ' . ini_get('session.cookie_lifetime') . '</li>';
	echo '<li><b>php.ini</b> value for <em>session.cookie_path</em> is: ' . ini_get('session.cookie_path') . '</li>';
	echo '<li><b>php.ini</b> value for <em>session.cookie_domain</em> is: ' . ini_get('session.cookie_domain') . '</li>';
	echo '<li><b>php.ini</b> value for <em>session.cookie_secure</em> is: ' . ini_get('session.cookie_secure') . '</li>';
	echo '<li><b>php.ini</b> value for <em>session.cookie_httponly</em> is: ' . ini_get('session.cookie_httponly') . '</li>';
echo '</ul>';
?>

<p>Invalidating all cookies...</p>
<?php
if(isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time() - 3600*24*365);
        setcookie($name, '', time() - 3600*24*365, '/', $cookie_domain);
		echo $name . ', ';
    }
}
?>

<p>Setting expiration date for requested cookies (PHP)...</p>
<?php
$past = time() - 3600*24*365;
echo '<textarea rows="10" cols="120">';
	foreach($_COOKIE as $key => $value) {
		setcookie($key, $value, $past, '/', $cookie_domain);
		setcookie($key, null, 1, '/', $cookie_domain);
		echo $key . ', ' . $value . ', ' . $past . '<br>';
	}
echo '</textarea>';
?>

<p>Resetting and invalidating all cookie sessions...</p>
<?php
session_start();
$CookieInfo = session_get_cookie_params();
if((empty($CookieInfo['domain'])) && (empty($CookieInfo['secure']))) {
	setcookie(session_name(), '', time()-3600, $CookieInfo['path']);
} elseif(empty($CookieInfo['secure'])) {
	setcookie(session_name(), '', time()-3600, $CookieInfo['path'], $CookieInfo['domain']);
} else {
	setcookie(session_name(), '', time()-3600, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure']);
}
session_destroy();
?>

<p>Setting expiration date for requested cookies (JS)...</p>
<script>
function createCookie(name,value,days) {
	if(days) {
		var date = new Date();
		date.setTime(date.getTime() + (days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name + "=" + value + expires + "; path=/";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if(c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function eraseCookie(name) {
	createCookie(name, "", -1);
}
var cookies = document.cookie.split(";");
for(var i=0; i < cookies.length; i++)
	eraseCookie(cookies[i].split("=")[0]);
</script>

<p>Done! Please restart your browser.</p>

</body>
</html>
