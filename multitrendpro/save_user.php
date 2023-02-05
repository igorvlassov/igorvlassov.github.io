<?php

if (isset($_GET['password'])) { $password=$_GET['password']; if ($password =='') { unset($password);} }
if (isset($_GET['email'])) { $email = $_GET['email']; if ($email == '') { unset($email);} } //������� ��������� ������������� e-mail, ���� �� ������, �� ���������� ����������
if (empty($password) or empty($email)) //���� ������������ �� ���� ����� ��� ������, �� ������ ������ � ������������� ������
{
exit ("Fill all required fields please!"); //������������� ���������� ���������

}
if (!preg_match("/[0-9a-z_-]+@[0-9a-z_^\.-]+\.[a-z]{2,3}/i", $email)) //�������� �-mail ������ ����������� ����������� �� ������������
{exit ("Invalid E-mail!");}


function generate_code() //��������� �������, ������������ ���
{
                
    $hours = date("H"); // ���       
    $minuts = substr(date("H"), 0 , 1);// ������ 
    $mouns = date("m");    // �����             
    $year_day = date("z"); // ���� � ����

    $str = $hours . $minuts . $mouns . $year_day; //������� ������
    $str = md5(md5($str)); //������ ������� � md5
	$str = strrev($str);// ������ ������
	$str = substr($str, 3, 6); // ��������� 6 ��������, ������� � 3
	// ��� ������� �� ����� ��������� ������ ��������, ��� ���, ���� ��������� ������, ����� ������ �������� ��� ��� ������������, �� � ������ �� ����� ������.
	

    $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    srand ((float)microtime()*1000000);
    shuffle ($array_mix);
	//��������� ������������, ����, ����� �� �����!!!
    return implode("", $array_mix);
}

function chec_code($code) //��������� ���
{
    $code = trim($code);//������� �������

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

//���� ����� � ������ �������,�� ������������ ��, ����� ���� � ������� �� ��������, ���� �� ��� ���� ����� ������
$email = stripslashes($email);
$email = htmlspecialchars($email);

$password = stripslashes($password);
$password = htmlspecialchars($password);

//������� ������ �������
$email = trim($email);
$password = trim($password);

//��������� �������� �� ����� ������ � ������
if (strlen($email) < 3) {

exit ("Login must be not shorter then 3 chars."); //������������� ���������� ���������

}
if (strlen($password) < 5 or strlen($password) > 15) {

exit ("Password must be not shorter then 5 chars & not longer then 15 chars."); //������������� ���������� ���������

}

$password = md5($password);//������� ������
$password = strrev($password);// ��� ���������� ������� ������

$password = $password."b3p6f";
//����� �������� ��������� ����� �������� �� �����, ��������, ������ "b3p6f". ���� ���� ������ ����� ���������� ������� ������� � ���� �� ������� ���� �� md5,�� ���� ������ �������� �� ������. �� ������� ������� ������ �������, ����� � ������ ������ ��� � ��������.

//��� ���� ���������� ��������� ����� ���� password � ����. ������������� ������ ����� ��������� ������� �������� �������.

// ������������ � ����
include ("bd.php");// ���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ���� 

// �������� �� ������������� ������������ � ����� �� �������
$result = mysql_query("SELECT id FROM users WHERE email='$email'",$db);
$myrow = mysql_fetch_array($result);
if (!empty($myrow['id'])) {

exit ("Login is already taken, sorry. Enter another login please."); //������������� ���������� ���������
}

// �������� �� ������������� ������������ � ����� �� email
$result = mysql_query("SELECT id FROM users WHERE email='$email'",$db);
$myrow = mysql_fetch_array($result);
if (!empty($myrow['id'])) {

exit ("Email is already used."); //������������� ���������� ���������
}

// ���� ������ ���, �� ��������� ������
$result2 = mysql_query ("INSERT INTO users (password,email,date) VALUES('$password','$email',NOW())");
// ���������, ���� �� ������
if ($result2==TRUE)
{
$result3 = mysql_query ("SELECT id FROM users WHERE email='$email'",$db);//��������� ������������� ������������. ��������� ��� � ��� � ����� ���������� ��� ���������, ���� ���� ���������� ��������������� ���� �� �����.
$myrow3 = mysql_fetch_array($result3);
$activation = md5($myrow3['id']).md5($email);//��� ��������� ��������. ��������� ����� ������� md5 ������������� � �����. ����� ��������� ������������ ���� �� ������ ��������� ������� ����� �������� ������.

$subject = "Sign Up Confirmation";//���� ���������

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
";//���������� ���������

$headers  = "Content-type: text/html; charset=windows-1251 \r\n";
$headers .= "From: ChebTrend4win Registration <signup@chebscan.com>\r\n";

mail($email, $subject, $message, $headers); //���������� ��������� "Content-type:text/plane; Charset=windows-1251\r\n"
	
echo "Check your email for confirmation letter please. The link will be available during 24 hours."; //������� � ������������ ������ ������������
}
else 
{
	exit ("Registration Error!"); //������������� ���������� ���������
}
?>