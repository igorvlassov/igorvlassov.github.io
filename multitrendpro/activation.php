<?php
include ("bd.php");// файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь 

mysql_query ("DELETE FROM users WHERE activation='0' AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 86400");//удаляем пользователей из базы

if (isset($_GET['login'])) {$login=$_GET['login']; } //логин,который нужно активировать
else { exit("login isn't provided!");} //если не указали логин, то выдаем ошибку

if (isset($_GET['code'])) {$code=$_GET['code']; } //логин,который нужно активировать
else { exit("Activation code isn't provided!");} //если не указали логин, то выдаем ошибку

$result = mysql_query("SELECT id FROM users WHERE email='$login'",$db); //извлекаем идентификатор пользователя с данным логином
$myrow = mysql_fetch_array($result); 

$activation = md5($myrow['id']).md5($login);//создаем такой же код подтверждения
if ($activation == $code) 
{//сравниваем полученный из url и сгенерированный код
	mysql_query("UPDATE users SET activation='1' WHERE email='$login'",$db);//если равны, то активируем пользователя
	echo "Email is confirmed! You can login to ChebTrend for Windows with your login now!";
}
else 
{
echo "Warning! Email isn't confirmed!";
//если же полученный из url и сгенерированный код не равны, то выдаем ошибку
}
?>