<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="en">
<title>SignUp</title>
<base target="_self">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body topmargin="0" leftmargin="0">
<table>
<tr><td bgcolor="#00a2e8">
<font face="Arial Black" size="5" color="#ffffff">SignUp</font>
</tr></td>
<tr><td>
<tr><td>
<br>
<form action="save_user.php" method="post" enctype="multipart/form-data">
    <label>Login *:<br></label>
    <input name="login" type="text" size="15" maxlength="15">
<br>
    <label>Password *:<br></label>
    <input name="password" type="password" size="15" maxlength="15">
<br>
    <label>E-mail *:<br></label>
    <input name="email" type="text" size="15" maxlength="100">
<br>
<input type="submit" name="submit" value="Sign Up">
</form>
(*) - Required Fields.
</td></tr>
</table>
</body>
</html>
