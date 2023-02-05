<?php
if(isset($_GET['hostuuid'])) { $hostuuid = $_GET['hostuuid']; if ($hostuuid == '') { unset($hostuuid);} } 

if (empty($hostuuid)) 
{
 exit ("ERROR\nComputer is not valid!"); 
}

$hostuuid = stripslashes($hostuuid);
$hostuuid = htmlspecialchars($hostuuid);
$hostuuid = trim($hostuuid);

include ("bd.php");

$result = mysql_query("SELECT * FROM multiTrendUsers WHERE hostuuid='$hostuuid'",$db);
$myrow = mysql_fetch_array($result);

if (empty($myrow['hostuuid']))
{
// Registration

    mysql_query ("INSERT INTO multiTrendUsers (date_reg, hostuuid) VALUES(NOW(), '$hostuuid')");
    exit("OK\nYou have 30 trial days left.");
}
else
{
    // if the end of trial period
    $dateReg = strtotime($myrow['date_reg']);

    // Month trial
    if(!$myrow['purchased'])
    {
	$dateTrialEnd = $dateReg + 2592000; // 30 days // 1209600; //14d*24h*60min*60s
        if($dateTrialEnd<time())
	{
	    exit("PERIODEND\nTrial period is over! Limited access only is allowed. Please Purchase the app if you like it.");
	}
        $daysLeft = round(($dateTrialEnd - time())/86400);
        exit("OK\nYou have $daysLeft days of full trial access left.");
    }
    
    echo "OK\nOK";
}

?>
