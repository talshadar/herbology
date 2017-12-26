<?php
##########################################################################
#  Herbology
#  Index Page
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

session_start();
/*
if (!$_SESSION['conoco']['account']['userID'])
{
	header("Location: login.php");
	exit;
}
*/

$title="";
$list="";

if (!$_GET && !$_POST)
{
  $list = listChoices();
}

include ('inc/header.inc.php');

?>

<table border="0" cellpadding="0" cellspacing="0" >
    <tr valign="middle">
        <td align="left"><br>
                        <?PHP echo $title; ?>
        </td>
    </tr>
    <tr>
        <td> <?PHP echo $list; ?> </td>
    </tr>
</table>

<?php


# now include the footer
include ('inc/footer.inc.php');

########  FUNCTIONS  #######

function listChoices()
{
    $list = '<p><a href="list_herbs.php" >Herb List</a></p>';
    $list .= '<p><a href="list_formulas.php" >Formula List</a></p>';
    $list .= '<p><a href="list_ailments.php" >Ailment List</a></p>';
    return $list;
}



?>