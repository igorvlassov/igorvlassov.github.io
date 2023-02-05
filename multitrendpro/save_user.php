<?php

if (isset($_GET['password'])) { $password=$_GET['password']; if ($password =='') { unset($password);} }
if (isset($_GET['email'])) { $email = $_GET['email']; if ($email == '') { unset($email);} } //заносим введенный пользователем e-mail, если он пустой, то уничтожаем переменную
if (empty($password) or empty($email)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
exit ("Fill all required fields please!"); //останавливаем выполнение сценариев

}
if (!preg_match("/[0-9a-z_-]+@[0-9a-z_^\.-]+\.[a-z]{2,3}/i", $email)) //проверка е-mail адреса регулярными выражениями на корректность
{exit ("Invalid E-mail!");}


function generate_code() //запускаем функцию, генерирующую код
{
                
    $hours = date("H"); // час       
    $minuts = substr(date("H"), 0 , 1);// минута 
    $mouns = date("m");    // месяц             
    $year_day = date("z"); // день в году

    $str = $hours . $minuts . $mouns . $year_day; //создаем строку
    $str = md5(md5($str)); //дважды шифруем в md5
	$str = strrev($str);// реверс строки
	$str = substr($str, 3, 6); // извлекаем 6 символов, начиная с 3
	// Вам конечно же можно постваить другие значения, так как, если взломщики узнают, каким именно способом это все генерируется, то в защите не будет смысла.
	

    $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    srand ((float)microtime()*1000000);
    shuffle ($array_mix);
	//Тщательно перемешиваем, соль, сахар по вкусу!!!
    return implode("", $array_mix);
}

function chec_code($code) //проверяем код
{
    $code = trim($code);//удаляем пробелы

    $array_mix = preg_split ('//', generate_code(), -1, PREG_SPLIT_NO_EMPTY);
    $m_code = preg_split ('//', $code, -1, PREG_SPLIT_NO_EMPTY);

    $result = array_intersect ($array_mix, $m_code);
if (strlen(generate_code())!=strlen($code))
{
    return FALSE;
}
if (sizeof($result) == sizeof($array_mix))
{
    return TRUE;
}
else
{
    return FALSE;
}
}

//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
$email = stripslashes($email);
$email = htmlspecialchars($email);

$password = stripslashes($password);
$password = htmlspecialchars($password);

//удаляем лишние пробелы
$email = trim($email);
$password = trim($password);

//добавляем проверку на длину логина и пароля
if (strlen($email) < 3) {

exit ("Login must be not shorter then 3 chars."); //останавливаем выполнение сценариев

}
if (strlen($password) < 5 or strlen($password) > 15) {

exit ("Password must be not shorter then 5 chars & not longer then 15 chars."); //останавливаем выполнение сценариев

}

$password = md5($password);//шифруем пароль
$password = strrev($password);// для надежности добавим реверс

$password = $password."b3p6f";
//можно добавить несколько своих символов по вкусу, например, вписав "b3p6f". Если этот пароль будут взламывать метадом подбора у себя на сервере этой же md5,то явно ничего хорошего не выйдет. Но советую ставить другие символы, можно в начале строки или в середине.

//При этом необходимо увеличить длину поля password в базе. Зашифрованный пароль может получится гораздо большего размера.

// подключаемся к базе
include ("bd.php");// файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь 

// проверка на существование пользователя с таким же логином
$result = mysql_query("SELECT id FROM users WHERE email='$email'",$db);
$myrow = mysql_fetch_array($result);
if (!empty($myrow['id'])) {

exit ("Login is already taken, sorry. Enter another login please."); //останавливаем выполнение сценариев
}

// проверка на существование пользователя с таким же email
$result = mysql_query("SELECT id FROM users WHERE email='$email'",$db);
$myrow = mysql_fetch_array($result);
if (!empty($myrow['id'])) {

exit ("Email is already used."); //останавливаем выполнение сценариев
}

// если такого нет, то сохраняем данные
$result2 = mysql_query ("INSERT INTO users (password,email,date) VALUES('$password','$email',NOW())");
// Проверяем, есть ли ошибки
if ($result2==TRUE)
{
$result3 = mysql_query ("SELECT id FROM users WHERE email='$email'",$db);//извлекаем идентификатор пользователя. Благодаря ему у нас и будет уникальный код активации, ведь двух одинаковых идентификаторов быть не может.
$myrow3 = mysql_fetch_array($result3);
$activation = md5($myrow3['id']).md5($email);//код активации аккаунта. Зашифруем через функцию md5 идентификатор и логин. Такое сочетание пользователь вряд ли сможет подобрать вручную через адресную строку.

$subject = "Sign Up Confirmation";//тема сообщения

$message = "
<html>
    <head> 
        <title>Market Scanner registration confirmation letter</title> 	
    </head> 
    <body>
Thanks for the registration! Your login is <b>$email</b><br>\n
Use the following link for account activation please:<br>\n
<a href=\"$ROOT/cheb4win/activation.php?login=$email&code=$activation\">Confirm registration</a><br>\n 
Cheers,<br>\n
Igor Vlasov
    </body>
</html>
";//содержание сообщения

$headers  = "Content-type: text/html; charset=windows-1251 \r\n";
$headers .= "From: ChebTrend4win Registration <signup@chebscan.com>\r\n";

mail($email, $subject, $message, $headers); //отправляем сообщение "Content-type:text/plane; Charset=windows-1251\r\n"
	
echo "Check your email for confirmation letter please. The link will be available during 24 hours."; //говорим о отправленном письме пользователю
}
else 
{
	exit ("Registration Error!"); //останавливаем выполнение сценариев
}
?>