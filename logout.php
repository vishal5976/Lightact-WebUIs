<?php
setcookie('username', '', time()-60*60*24*365);
setcookie('password', '', time()-60*60*24*365);

header('Location: login.php');

?>