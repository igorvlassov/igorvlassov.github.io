<?php
include ("bd.php");// ���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ���� 

mysql_query ("DELETE FROM users WHERE activation='0' AND UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 86400");//������� ������������� �� ����

if (isset($_GET['login'])) {$login=$_GET['login']; } //�����,������� ����� ������������
else { exit("login isn't provided!");} //���� �� ������� �����, �� ������ ������

if (isset($_GET['code'])) {$code=$_GET['code']; } //�����,������� ����� ������������
else { exit("Activation code isn't provided!");} //���� �� ������� �����, �� ������ ������

$result = mysql_query("SELECT id FROM users WHERE email='$login'",$db); //��������� ������������� ������������ � ������ �������
$myrow = mysql_fetch_array($result); 

$activation = md5($myrow['id']).md5($login);//������� ����� �� ��� �������������
if ($activation == $code) 
{//���������� ���������� �� url � ��������������� ���
	mysql_query("UPDATE users SET activation='1' WHERE email='$login'",$db);//���� �����, �� ���������� ������������
	echo "Email is confirmed! You can login to ChebTrend for Windows with your login now!";
}
else 
{
echo "Warning! Email isn't confirmed!";
//���� �� ���������� �� url � ��������������� ��� �� �����, �� ������ ������
}
?>