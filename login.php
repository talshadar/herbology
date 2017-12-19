<?php
##########################################################################
#  DocDwgID Creator 
#  login page
#
#  Matthew Bryan
#  Jan 23, 2012
##########################################################################
#include the header
session_start();

/*
if (($_SESSION['access'] != "granted") || !(($_POST[userID]) && ($_POST[password])) )
{
	header("Location: login.php");
	exit;
}
*/
require_once 'classes/class.account.inc.php';

if ($_POST)
{

  if ( ($_POST[userID]) && ($_POST[password]) )
	{
	  $account = new account;
		$account_info = $account->checkLogin($_POST[userID],$_POST[password]);
		/*
		echo "<pre>";
		print_r($account_info);
		echo "</pre>";
		*/
	  $_SESSION['access'] = "granted";
	  header("Location: admin.php");
	  exit;		
	}
	else
	{
  	foreach ($_POST as $name=>$value)
    {
    	
    	$action =  $_POST['action'];
    	$which = $_POST['which'];
    	$msg = $_POST['msg'];
    
    	if ($name=="logout" || $value=="logout")
    	{
    		session_destroy();
    		$loggedout = 1;
    	}
  	  header("Location: herb.php");
  	  exit;				

    }//end POST foreach loop
	}//end else $POST Login
}
else
{
  include ('inc/header.inc.php');
  ?>
  
  <table border="0" cellpadding="0" cellspacing="0" width = "95%">
  	<?
  		if ($loggedout)
  		{
  			?>
  				<tr><td><h2>LOGGED OUT</h2></td></tr>
  			<?
  		}
  	?>
  	<tr valign="middle">
  		<td align="left">
  			<H2>Login Form</H2>
  			<FORM METHOD="POST" ACTION="login.php">
  			<P><STRONG>Username:</STRONG><BR>
  			<INPUT TYPE="text" NAME="userID"></p>
  			<P><STRONG>Password:</STRONG><BR>
  			<INPUT TYPE="password" NAME="password"></p>
  			<P><INPUT TYPE="SUBMIT" NAME="submit" VALUE="Login"></P>
  			</FORM>
  		</td>
  	</tr>
  </table>
  
  <?
}





# now include the footer
include ('inc/footer.inc.php');

?>