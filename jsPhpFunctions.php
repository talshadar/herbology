<?php
##########################################################################
#  Herbology
#  function Page
#
#  Matthew Bryan
#  Mar 18, 2018
##########################################################################
// Report all PHP errors (see changelog)
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//ini_set('memory_limit', '-1');

session_start();

include_once('classes/class.herbs.inc.php');
include_once('classes/class.properties.inc.php');
include_once('classes/class.energetics.inc.php');
include_once('classes/class.ailments.inc.php');
include_once('classes/class.parts.inc.php');
include_once('classes/class.body.inc.php');

$which = $_GET['which'];
$id = $_GET['id'];

if ($which == "getProperty")
{
    echo getProperty($id);
}
elseif ($which == "getEnergetic")
{
    echo getEnergetic($id);
}
elseif ($which == "getAilment")
{
    echo getAilment($id);    
}

function  getProperty($propId)
{
    //echo "getProp function" . $propId;
    
    $properties = new properties();
    
    $herbPropInfo = $properties->get_property($propId);
    
    //echo "<pre>";
    //print_r($herbPropInfo);
    //echo "</pre>";
    
    $result = $herbPropInfo[0][1] . ": " . $herbPropInfo[0][2];
    
    //echo $result;
    
    return $result;
}

function  getEnergetic($energId)
{
    //echo "getProp function" . $propId;
    
    $energetics = new energetics();
    
    $herbEnergInfo = $energetics->get_energetic($energId);
    
    //echo "<pre>";
    //print_r($herbPropInfo);
    //echo "</pre>";
    
    $result = $herbEnergInfo[0][1] . ": " . $herbEnergInfo[0][2];
    
    //echo $result;
    
    return $result;
}

function  getAilment($ailId)
{
    //echo "getProp function" . $propId;
    
    $ailments = new ailments();
    
    $herbAilInfo = $ailments->get_ailment($ailId);
    
    //echo "<pre>";
    //print_r($herbAilInfo);
    //echo "</pre>";
    
    $result = $herbAilInfo[0][1] . ": " . $herbAilInfo[0][2];
    
    //echo $result;
    
    return $result;
}

?>