<?php
include ("bd.php");// файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь 

//print_r($_POST);

error_log(print_r($_POST,true));

if(!isset($_POST["EMAIL"]))
{
    die("ERROR\nVendorID is absent!");
}
$email=$_POST["EMAIL"];

$result = mysql_query("SELECT * FROM multiTrendUsers WHERE hostuuid='$email'",$db); //извлекаем из базы все данные о пользователе с введенным логином
$myrow = mysql_fetch_array($result);

if (empty($myrow['hostuuid']))
{
  die("ERROR\nUser isn't registered!");
}

$vendor_id = $myrow['hostuuid'];
$date_reg = $myrow['date_reg'];

$result = mysql_query("update multiTrendUsers set purchased=TRUE WHERE hostuuid='$vendor_id'",$db); 

echo "OK\nSuccess!";

?>
