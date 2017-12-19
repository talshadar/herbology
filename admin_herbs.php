<?php
##########################################################################
#  herbology
#  admin page
#
#  Matthew Bryan
#  nov 10, 2017
##########################################################################
#include the header
session_start();

if (($_SESSION['access'] != "granted"))
{
	header("Location: login.php");
	exit;
}

include_once('classes/class.herbs.inc.php');
include_once('classes/class.properties.inc.php');
include_once('classes/class.energetics.inc.php');
include_once('classes/class.ailments.inc.php');
include_once('classes/class.parts.inc.php');
include_once('classes/class.body.inc.php');

$list = "";

  include ('inc/header.inc.php');
  ?>
	
<!-- The Modal -->
<div id="myModal" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" id="myModalTitle">
      </div>
      <div class="modal-body" id="myModalContent">
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

	
<script>

function goBack() {
    window.history.back();
}

function listHerbs(){
  window.location = "admin_herbs.php?action=list&which=herbs";
}

function addHerb(){
  window.location = "admin_herbs.php?action=add";
}

function admin(){
  window.location = "admin.php";
}

</script>
 <?

if (!$_GET && !$_POST)
{
  $list = listHerbs();
}
else
{
  
	$action =  $_GET['action'];
	$which = $_GET['which'];
	$order = $_GET['order'];
	$filter = $_GET['filter'];
	$id = $_GET['id'];
	$msg = $_GET['msg'];
	
	/*
	echo "</br>Action:" . $action;
	echo "</br>Which:" . $which;
	echo "</br>ID:" . $id;
	echo "</br>Message:" . $msg;
	echo "<pre>POST:";
	print_r($_POST);
	echo "</pre>";
	*/
	
	if ($action=="list")
	{
	  if ($which == "herbs")
		{
  		$title = "List Herbs";			
  		$list .= listHerbs($filter, $order);		
		}
	}
	elseif ($action == "edit" && !empty($id) )
	{
	  //echo "<br>EDIT:" . $which . "-" . $id;
	  if ($which == "herb")
		{
  		$title = "Edit Herb: ";
  		$list .= editHerb($id);		
		}
	}
	elseif ($action=='update')
	{
		$list .= updateHerb();
		//$list .= listHerbs();
	}
	elseif ($action=='save')
	{
	  $list .= saveHerb();
		$title = "Herb Added";
		//$list .= listHerbs();
	}
	elseif ($action=='add')
	{
		$list .= addHerb();
	}

}


 if ( $_GET ){ ?>
 <div class="menu">
   <button onclick="addHerb()">Add New Herb</button>
   <button onclick="listHerbs()">List Herbs</button>
   <button onclick="admin()">Admin</button>
 </div>
<? } ?> 
  <table border="0" cellpadding="0" cellspacing="0" width = "95%">
		<TR>
		  <td class="titleBlock"> <? echo $title; ?>
			</td>
		</tr>
  	<tr >
  		<td >
			   <? echo $list; ?>
  		</td>
  	</tr>
  </table>
  
  <?

  # now include the footer
  include ('inc/footer.inc.php');

	
#################  FUNCTIONS  ##############

function listHerbs($filter="", $order="")
{
	$herbs = new herbs;
	$herbList = $herbs->get_herbs($filter, $order);
	$properties = new properties();
	$energetics = new energetics();
	$ailments = new ailments();

  if (!is_null($herbList))
	{
  	foreach ($herbList as $count => $data)
  	{
  			$herbInfo[$count]['herbID'] = $data[0];
  			$herbInfo[$count]['herb'] = $data[1];
  			$herbInfo[$count]['warning'] = $data[5];
				$propInfo ="";
				
				$propertyList = $properties->get_herb_properties($data[0]);
				if (!is_null($propertyList))
				{
        	
        	//echo "Properties:<pre>";
         	//print_r($propertyList);
         	//echo "</pre>";
					
					foreach ($propertyList as $propCount => $propData)
					{
					    $propInfo .= $propData[0];
							if ($propCount < count($propertyList)-1)
							{
							  $propInfo .= ", ";
							}
					}
					//echo $propInfo . "<br>";
				}
				
				$herbInfo[$count]['properties'] = $propInfo;
				$energInfo ="";
				$energeticList = $energetics->get_herb_energetics($data[0]);
				if (!is_null($energeticList))
				{
        	
        	//echo "Properties:<pre>";
         	//print_r($propertyList);
         	//echo "</pre>";
					
					foreach ($energeticList as $energCount => $energData)
					{
					    $energInfo .= $energData[0];
							if ($energCount < count($energeticList)-1)
							{
							  $energInfo .= ", ";
							}
					}
					//echo $energInfo . "<br>";
				}
				
				$herbInfo[$count]['energetics'] = $energInfo;

				$ailmentInfo ="";
				$ailmentList = $ailments->get_herb_ailments($data[0]);
				if (!is_null($ailmentList))
				{
        	
        	//echo "Properties:<pre>";
         	//print_r($propertyList);
         	//echo "</pre>";
					
					foreach ($ailmentList as $ailmentCount => $ailmentData)
					{
					    $ailmentInfo .= $ailmentData[0];
							if ($ailmentCount < count($ailmentList)-1)
							{
							  $ailmentInfo .= ", ";
							}
					}
					//echo $energInfo . "<br>";
				}
				
				$herbInfo[$count]['ailments'] = $ailmentInfo;

				
  	}	
	}

  /*
 	echo "Sorted<pre>";
 	print_r($herbInfo);
 	echo "</pre>";
	*/	
		
	$rowStyle == 'rowoff';
	//now start looping through the users info
  if (!is_null($herbInfo))
  {
  	foreach ($herbInfo as $id => $data)
  	{
  		$rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
	    $list .= '<p class="' . $rowStyle . '">';
			$list .= '<a href="admin_herbs.php?action=edit&which=herb&id=' . $data['herbID'] . '" >';
   		$list .= $data['herb']. "&nbsp;";
			$list .= '</a><br/>';
			if ($data['latin_name'] != "")
			{
     		 $list .= $data['latin_name']. "<br/>";
			}
			if ($data['other_names'] != "")
			{
   		   $list .= $data['other_names']. "<br/>";
			}
			if ($data['warning'] != "")
			{
			   $list .= "<strong>WARNING: " . $data['warning']. "</strong><br/>";
			}			
			if ($data['properties'] != "")
			{
   		   $list .= "Properties: " . $data['properties'] . "<br/>";
			}
			if ($data['energetics'] != "")
			{
   		   $list .= "Energetics: " . $data['energetics'] . "<br/>";
			}
			if ($data['ailments'] != "")
			{
   		   $list .= "<strong>Ailments:</strong> " . $data['ailments'] . "<br/>";
			}
			if ($data['bodies'] != "")
			{
   		   $list .= "Bodies: " . $data['bodies'] . "<br/>";
			}
	    $list .= "</p>";
  	}
  }
	
	
	return $list;
	
}//end listHerbs

function addHerb()
{
  global $title;
	$herbs = new herbs;
	$properties = new properties();
	$energetics = new energetics();
	$ailments = new ailments();
	$bodies = new body();
	
	$allProperties = $properties->get_properties();
 	$allEnergetics = $energetics->get_energetics();
 	$allAilments = $ailments->get_ailments();
 	$allBodies = $bodies->get_bodies();

?>

<script>
var property = {};
var propDef = {};

var energetic = {};
var energDef = {};

var ailments = {};
var ailmentDef = {};

var body = {};
var bodyDef = {};

<?		

			//must set propInfo and enerInfo as arrays in case they have no data
			$propInfo[0] = "";
			$enerInfo[0] = "";
			$ailmentInfo[0] = "";
			$bodyInfo[0] = "";
		
    	foreach ($allProperties as $propCount => $propData)
    	{
         echo 'property["'. $propData[0]. '"] = "'. $propData[1] .'";';
         echo 'propDef["'. $propData[0]. '"] = '. json_encode($propData[2]) .';';
			}

    	foreach ($allEnergetics as $energCount => $energData)
    	{
			    echo 'energetic["'. $energData[0]. '"] = "' . $energData[1] . '";';
			    echo 'energDef["'. $energData[0]. '"] = '. json_encode($energData[2]) .';';
			}

    	foreach ($allAilments as $ailmentCount => $ailmentData)
    	{
         echo 'ailment["'. $ailmentData[0]. '"] = "'. $ailmentData[1] .'";';
         echo 'ailmentDef["'. $ailmentData[0]. '"] = '. json_encode($ailmentData[2]) .';';
			}

    	foreach ($allBodies as $bodyCount => $bodyData)
    	{
			    echo 'body["'. $bodyData[0]. '"] = "' . $bodyData[1] . '";';
			    echo 'bodyDef["'. $bodyData[0]. '"] = '. json_encode($bodyData[2]) .';';
			}

?>


$(document).on("click", ".propLink", function() {
  var id = $(this).attr("propid");
	var defTitle = 'Definition: ' + property[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(propDef[id]);
  $("#myModal").modal("show");
});

$(document).on("click", ".enerLink", function() {
  var id = $(this).attr("enerid");
	var defTitle = 'Definition: ' + energetic[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(energDef[id]);
  $("#myModal").modal("show");
});

$(document).on("click", ".ailmentLink", function() {
  var id = $(this).attr("ailmentid");
	var defTitle = 'Definition: ' + ailment[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(ailmentDef[id]);
  $("#myModal").modal("show");
});

$(document).on("click", ".bodyLink", function() {
  var id = $(this).attr("bodyid");
	var defTitle = 'Definition: ' + body[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(bodyDef[id]);
  $("#myModal").modal("show");
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
       myModal.style.display = "none";
    }
}



</script>
<?
		/*
    echo "<pre>";
		print_r($allProperties);
		print_r($allEnergetics);
		echo "</pre>";
		*/
		
		$list = '';
    $list .= '  <table border="0" cellpadding="0" cellspacing="0" >';    //width = "800px"
    $list .= '  	<tr valign="middle">';
    $list .= '  		<td align="left">';
		 
      $list .= '      <table border="0" cellpadding="0" cellspacing="0" width = "100%">';
      $list .= '  			<FORM METHOD="POST" ACTION="admin_herbs.php?action=save">';

  		$list .= '        <td>';
      $list .= '  			<P><STRONG>Herb:</STRONG><BR>';
      $list .= '  			<INPUT TYPE="text" SIZE="40" NAME="herb" value=""></p>';
			$list .= '        </td>';
  
  		$list .= '        <td rowspan="3">';
      $list .= '  			<P><STRONG>Description:</STRONG><BR>';
      $list .= '  			<textarea rows="6" cols="80" NAME="description"></textarea></p>';
      $list .= '        </td></tr>';
  
  		$list .= '        <tr><td>';
      $list .= '  			<P><STRONG>Latin Name:</STRONG><BR>';
      $list .= '  			<INPUT TYPE="text" SIZE="40" NAME="latin_name" value=""></p>';
  		$list .= '        </td></tr>';
  		
  		$list .= '        <tr><td>';
      $list .= '  			<P><STRONG>Other Names:</STRONG><BR>';
      $list .= '  			<INPUT TYPE="text" SIZE="40" NAME="other_names" value=""></p>';
      $list .= '        </td></tr>';
  		
  		$list .= '        <tr><td colspan="2">';
      $list .= '  			<P><STRONG>Warning:</STRONG><BR>';
      $list .= '  			<textarea rows="3" cols="130" NAME="warning"></textarea></p>';
      $list .= '        </td></tr>';
  		
  		$list .= '        <tr><td colspan="2">';
      $list .= '  			<P><STRONG>Food Use/Nutritional:</STRONG><BR>';
      $list .= '  			<textarea rows="3" cols="130" NAME="food"></textarea></p>';
      $list .= '        </td></tr>';
			
//need to list out the different properties, energetics etc. check boxes			

			$colCount = 0;
			
			$list .= '<tr><td colspan="2"><STRONG>Herb Properties</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';

			$propPiece = '<tr class="' . $rowStyle . '">';
			foreach ($allProperties as $propCount => $propData)
			{
			    if ($colCount < 8)
					{
					   $propPiece .= '<td >';
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $propPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $propPiece .= '<td width="105px">';
						 $colCount = 0;
					}
				 //$propPiece .= '<input type="checkbox" name="prop'.$propData[0].'" value="'.$propData[0] . '"';
				 $propPiece .= '<input type="checkbox" name="properties[]" value="'.$propData[0] . '"';
				 $propPiece .= ' >&nbsp;';
				 $propPiece .=  '<a class="propLink" propid="' . $propData[0] .'">' . $propData[1] . '</a><br>';
				 $propPiece .= '</td>';
				 
				 $colCount = $colCount+1;

			}//end for properties loop
			
			$list .= $propPiece . '</tr></table></td></tr>';  //need to a end row.
			
			//now energetics
			$list .= '<tr><td colspan="2"><STRONG>Herb Energetics</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';
			$colCount = 0;
			$enerPiece = '<tr class="' . $rowStyle . '">';
			foreach ($allEnergetics as $enerCount => $enerData)
			{
			    if ($colCount < 8)
					{
					   $enerPiece .= '<td width="105px">';
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $enerPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $enerPiece .= '<td width="105px">';
						 $colCount = 0;
					}
				 //$enerPiece .= '<input type="checkbox" name="ener-'.$enerData[0].'" value="'.$enerData[0] . '"';
				 $enerPiece .= '<input type="checkbox" name="energetics[]" value="'.$enerData[0] . '"';
				 $enerPiece .= ' >&nbsp;';
				 $enerPiece .=  '<a class="enerLink" enerid="' . $enerData[0] .'">' . $enerData[1] . '</a><br>';
				 $enerPiece .= '</td>';
				 
				 $colCount = $colCount+1;
				 

			}//end for energetics loop
			
			$list .= $enerPiece . '</tr></table></td></tr>';  //need to a end row.
			
			$list .= '<tr><td colspan="2"><STRONG>Herb - Body Parts Affected</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';
			$colCount = 0;
			
			$bodyPiece = '<tr class="' . $rowStyle . '">';
			foreach ($allBodies as $bodyCount => $bodyData)
			{
			    if ($colCount < 8)
					{
					   $bodyPiece .= '<td width="105px">';
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $bodyPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $bodyPiece .= '<td width="105px">';
						 $colCount = 0;
					}
				 //$enerPiece .= '<input type="checkbox" name="ener-'.$enerData[0].'" value="'.$enerData[0] . '"';
				 $bodyPiece .= '<input type="checkbox" name="bodies[]" value="'.$bodyData[0] . '"';
				 $bodyPiece .= ' >&nbsp;';
				 $bodyPiece .=  '<a class="bodyLink" bodyid="' . $bodyData[0] .'">' . $bodyData[1] . '</a><br>';
				 $bodyPiece .= '</td>';
				 
				 $colCount = $colCount+1;				 

			}//end for bodies loop			
			
			$list .= $bodyPiece . '</tr></table></td></tr>';  //need to a end row.

			//now ailment
			$list .= '<tr><td colspan="2"><STRONG>Herb Ailment</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';
			$colCount = 0;
			$ailmentPiece = '<tr class="' . $rowStyle . '">';
			foreach ($allAilments as $ailmentCount => $ailmentData)
			{
			    if ($colCount < 8)
					{
					   $ailmentPiece .= '<td width="105px">';
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $ailmentPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $ailmentPiece .= '<td width="105px">';
						 $colCount = 0;
					}
				 //$enerPiece .= '<input type="checkbox" name="ener-'.$enerData[0].'" value="'.$enerData[0] . '"';
				 $ailmentPiece .= '<input type="checkbox" name="ailment[]" value="'.$ailmentData[0] . '"';
				 $ailmentPiece .= ' >&nbsp;';
				 $ailmentPiece .=  '<a class="ailmentLink" ailmentid="' . $ailmentData[0] .'">' . $ailmentData[1] . '</a><br>';
				 $ailmentPiece .= '</td>';
				 
				 $colCount = $colCount+1;
				 

			}//end for ailment loop
			
			$list .= $ailmentPiece . '</tr></table></td></tr>';  //need to a end row.
			
			
  		$list .= '        <tr><td>';
      $list .= '  			<P><INPUT TYPE="SUBMIT" NAME="submit" VALUE="Save"></P>';
  		$list .= '        </td></td>';
      $list .= '  			</FORM>';
  		$list .= '      </table>';
		
		
    $list .= '  		</td>';
    $list .= '  	</tr>';
    $list .= '  </table>';
		
	
  return $list;
}//end addNewHerb


function editHerb($herbID)
{
  global $title;
	$herbs = new herbs;
	$properties = new properties();
	$energetics = new energetics();
	$ailments = new ailments();
	$bodies = new body();
	
	$herbList = $herbs->get_herb($herbID);
	
	
	//echo "herbs:<pre>";
 	//print_r($herbList);
 	//echo "</pre>";
	
	
  if (!is_null($herbList))
	{
			$herbInfo['index'] = $herbList[0][0];
			$herbInfo['herb'] = $herbList[0][1];
			$herbInfo['latin_name'] = $herbList[0][2];
			$herbInfo['other_names'] = $herbList[0][3];
			$herbInfo['description'] = $herbList[0][4];
			$herbInfo['warning'] = $herbList[0][5];
			$herbInfo['nutritional'] = $herbList[0][6];
	}//end ifNull check for $herbList	
	
	$title .= $herbInfo['herb'];
			
?>

<script>
var property = {};
var propDef = {};

var energetic = {};
var energDef = {};

var ailment = {};
var ailmentDef = {};

var body = {};
var bodyDef = {};

<?		

			//must set propInfo and enerInfo as arrays in case they have no data
			$propInfo[0] = "";
			$enerInfo[0] = "";
			$ailmentInfo[0] = "";
			$bodyInfo[0] = "";			

    	$propertyList[0] = "";
    	$propertyList = $properties->get_herb_properties($herbID);
    	$allProperties = $properties->get_properties();
			
			if (is_array($propertyList))
			{
      	foreach ($propertyList as $propCount => $propData)
      	{
      	    $propInfo[$propCount] =  $propData[2];
      	}//end for properties loop
			}
    	foreach ($allProperties as $propCount => $propData)
    	{
         echo 'property["'. $propData[0]. '"] = "'. $propData[1] .'";';
         echo 'propDef["'. $propData[0]. '"] = '. json_encode($propData[2]) .';';
			}
			
			$energeticList[0] = "";
  		$energeticList = $energetics->get_herb_energetics($herbID);
  	  $allEnergetics = $energetics->get_energetics();
		
		  if (is_array($energeticList))
			{
  			foreach ($energeticList as $energCount => $energData)
  			{
  			    $enerInfo[$energCount] =  $energData[2];					
  			}
			}
			
    	foreach ($allEnergetics as $energCount => $energData)
    	{
			    echo 'energetic["'. $energData[0]. '"] = "' . $energData[1] . '";';
			    echo 'energDef["'. $energData[0]. '"] = '. json_encode($energData[2]) .';';
			}
			
			
			$bodyList[0] = "";
  		$bodyList = $bodies->get_herb_bodies($herbID);
  	  $allBodies = $bodies->get_bodies();
		
		  if (is_array($bodyList))
			{
  			foreach ($bodyList as $bodyCount => $bodyData)
  			{
  			    $bodyInfo[$bodyCount] =  $bodyData[2];
  			}
			}			
    	foreach ($allBodies as $bodyCount => $bodyData)
    	{
			    echo 'body["'. $bodyData[0]. '"] = "' . $bodyData[1] . '";';
			    echo 'bodyDef["'. $bodyData[0]. '"] = '. json_encode($bodyData[2]) .';';
			}
			
			$ailmentList[0] = "";
  		$ailmentList = $ailments->get_herb_ailments($herbID);
  	  $allAilments = $ailments->get_ailments();
		
		  if (is_array($ailmentList))
			{
  			foreach ($ailmentList as $ailmentCount => $ailmentData)
  			{
  			    $ailmentInfo[$ailmentCount] =  $ailmentData[2];					
  			}
			}
			
    	foreach ($allAilments as $ailmentCount => $ailmentData)
    	{
         echo 'ailment["'. $ailmentData[0]. '"] = "'. $ailmentData[1] .'";';
         echo 'ailmentDef["'. $ailmentData[0]. '"] = '. json_encode($ailmentData[2]) .';';
			}
			
?>


$(document).on("click", ".propLink", function() {
  var id = $(this).attr("propid");
	var defTitle = 'Definition: ' + property[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(propDef[id]);
  $("#myModal").modal("show");
});

$(document).on("click", ".enerLink", function() {
  var id = $(this).attr("enerid");
	var defTitle = 'Definition: ' + energetic[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(energDef[id]);
  $("#myModal").modal("show");
});

$(document).on("click", ".ailmentLink", function() {
  var id = $(this).attr("ailmentid");
	var defTitle = 'Definition: ' + ailment[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(ailmentDef[id]);
  $("#myModal").modal("show");
});


$(document).on("click", ".bodyLink", function() {
  var id = $(this).attr("bodyid");
	var defTitle = 'Definition: ' + body[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(bodyDef[id]);
  $("#myModal").modal("show");
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
       myModal.style.display = "none";
    }
}



</script>
<?
		
    //echo "<pre>";
		//print_r($herbInfo);
		//print_r($propertyList);
		//print_r($allProperties);
		//print_r($energeticList);
		//print_r($allEnergetics);
		//print_r($bodyList);
		//print_r($allBodies);
		//print_r($bodyInfo);
		//echo "</pre>";
		
		
		
		$list = '';
    $list .= '  <table border="0" cellpadding="0" cellspacing="0" >';  //width = "800px"
    $list .= '  	<tr valign="middle">';
    $list .= '  		<td align="left">';
		
		 
      $list .= '      <table border="0" cellpadding="0" cellspacing="0" width = "100%">';
      $list .= '  			<FORM METHOD="POST" ACTION="admin_herbs.php?action=update">';
  		$list .= '				<INPUT TYPE="hidden" NAME="id" value="' . $herbID . '">';

  		$list .= '        <td>';
      $list .= '  			<P><STRONG>Herb:</STRONG><BR>';
      $list .= '  			<INPUT TYPE="text" SIZE="40" NAME="herb" value="' . $herbInfo['herb'] . '"></p>';
			$list .= '        </td>';
  
  		$list .= '        <td rowspan="3">';
      $list .= '  			<P><STRONG>Description:</STRONG><BR>';
      $list .= '  			<textarea rows="6" cols="80" NAME="description">' . $herbInfo['description'] . '</textarea></p>';
      $list .= '        </td></tr>';
  
  		$list .= '        <tr><td>';
      $list .= '  			<P><STRONG>Latin Name:</STRONG><BR>';
      $list .= '  			<INPUT TYPE="text" SIZE="40" NAME="latin_name" value="' . $herbInfo['latin_name'] . '"></p>';
  		$list .= '        </td></tr>';
  		
  		$list .= '        <tr><td>';
      $list .= '  			<P><STRONG>Other Names:</STRONG><BR>';
      $list .= '  			<INPUT TYPE="text" SIZE="40" NAME="other_names" value="' . $herbInfo['other_names'] . '"></p>';
      $list .= '        </td></tr>';
  		
  		$list .= '        <tr><td colspan="2">';
      $list .= '  			<P><STRONG>Warning:</STRONG><BR>';
      $list .= '  			<textarea rows="3" cols="130" NAME="warning">' . $herbInfo['warning'] . '</textarea></p>';
      $list .= '        </td></tr>';
  		
  		$list .= '        <tr><td colspan="2">';
      $list .= '  			<P><STRONG>Food Use/Nutritional:</STRONG><BR>';
      $list .= '  			<textarea rows="3" cols="130" NAME="food">' . $herbInfo['nutritional'] . '</textarea></p>';
      $list .= '        </td></tr>';
			
//need to list out the different properties, energetics etc. check boxes			

			$colCount = 0;
			
			$list .= '<tr><td colspan="2"><STRONG>Herb Properties</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';

			$propPiece = '<tr class="' . $rowStyle . '">';
			foreach ($allProperties as $propCount => $propData)
			{
			    if ($colCount < 8)
					{
					   $propPiece .= '<td >';  //width="105px"
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $propPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $propPiece .= '<td >';  //width="105px"
						 $colCount = 0;
					}
				 //$propPiece .= '<input type="checkbox" name="prop'.$propData[0].'" value="'.$propData[0] . '"';
				 $propPiece .= '<input type="checkbox" name="properties[]" value="'.$propData[0] . '"';
				 if (in_array($propData[0], $propInfo))
				 {
				    //echo "<br>equal:" . $propData[0];
				    $propPiece .= " checked";
				 }
				 $propPiece .= ' >&nbsp;';
				 $propPiece .=  '<a class="propLink" propid="' . $propData[0] .'">' . $propData[1] . '</a><br>';
				 //$propInfo .= '<a class="propLink" propid="' . $propCount .'">' . $propData[0] . '</a>';
				 $propPiece .= '</td>';
				 
				 $colCount = $colCount+1;
				 

			}//end for properties loop
			
			$list .= $propPiece . '</tr></table></td></tr>';  //need to a end row.
			
			//now energetics
			$list .= '<tr><td colspan="2"><STRONG>Herb Energetics</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';
			$colCount = 0;
			$enerPiece = '<tr class="' . $rowStyle . '">';
			foreach ($allEnergetics as $enerCount => $enerData)
			{
			    if ($colCount < 8)
					{
					   $enerPiece .= '<td >';  //width="105px"
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $enerPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $enerPiece .= '<td >';  //width="105px"
						 $colCount = 0;
					}
				 //$enerPiece .= '<input type="checkbox" name="ener-'.$enerData[0].'" value="'.$enerData[0] . '"';
				 $enerPiece .= '<input type="checkbox" name="energetics[]" value="'.$enerData[0] . '"';
				 if (in_array($enerData[0], $enerInfo))
				 {
				    //echo "<br>equal:" . $propData[0];
				    $enerPiece .= " checked";
				 }
				 $enerPiece .= ' >&nbsp;';
				 $enerPiece .=  '<a class="enerLink" enerid="' . $enerData[0] .'">' . $enerData[1] . '</a><br>';
				 //$propInfo .= '<a class="propLink" propid="' . $propCount .'">' . $propData[0] . '</a>';
				 $enerPiece .= '</td>';
				 
				 $colCount = $colCount+1;
				 

			}//end for energetics loop
			
			$list .= $enerPiece . '</tr></table></td></tr>';  //need to a end row.

			$list .= '<tr><td colspan="2"><STRONG>Herb - Body Parts Affected</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';
			$colCount = 0;
			
			$bodyPiece = '<tr class="' . $rowStyle . '">';
			foreach ($allBodies as $bodyCount => $bodyData)
			{
			    if ($colCount < 8)
					{
					   $bodyPiece .= '<td >';  //width="105px"
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $bodyPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $bodyPiece .= '<td >';  //width="105px"
						 $colCount = 0;
					}
				 //$enerPiece .= '<input type="checkbox" name="ener-'.$enerData[0].'" value="'.$enerData[0] . '"';
				 $bodyPiece .= '<input type="checkbox" name="bodies[]" value="'.$bodyData[0] . '"';
				 //echo "</br>bodyid:" . $bodyData[0];
				 if (in_array($bodyData[0], $bodyInfo))
				 {
				    //echo "<br>equal:" . $propData[0];
				    $bodyPiece .= " checked";
				 }				 
				 $bodyPiece .= ' >&nbsp;';
				 $bodyPiece .=  '<a class="bodyLink" bodyid="' . $bodyData[0] .'">' . $bodyData[1] . '</a><br>';
				 $bodyPiece .= '</td>';
				 
				 $colCount = $colCount+1;				 

			}//end for bodies loop
			
			$list .= $bodyPiece . '</tr></table></td></tr>';  //need to a end row.			
			
			//now ailment
			$list .= '<tr><td colspan="2"><STRONG>Herb Ailment</strong></td></tr>';
  		$list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

			$rowStyle = 'rowoff';
			$colCount = 0;
			$ailmentPiece = '<tr class="' . $rowStyle . '">';
			
			foreach ($allAilments as $ailmentCount => $ailmentData)
			{
			    if ($colCount < 8)
					{
					   $ailmentPiece .= '<td >';  //width="105px"
					}
					else
					{
  		   	   $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
					   $ailmentPiece .= '</tr><tr class="' . $rowStyle . '">';
						 $ailmentPiece .= '<td >';  //width="105px"
						 $colCount = 0;
					}
				 //$enerPiece .= '<input type="checkbox" name="ener-'.$enerData[0].'" value="'.$enerData[0] . '"';
				 $ailmentPiece .= '<input type="checkbox" name="ailments[]" value="'.$ailmentData[0] . '"';
				 if (in_array($ailmentData[0], $ailmentInfo))
				 {
				    //echo "<br>equal:" . $propData[0];
				    $ailmentPiece .= " checked";
				 }
				 $ailmentPiece .= ' >&nbsp;';
				 $ailmentPiece .=  '<a class="ailmentLink" ailmentid="' . $ailmentData[0] .'">' . $ailmentData[1] . '</a><br>';
				 //$propInfo .= '<a class="propLink" propid="' . $propCount .'">' . $propData[0] . '</a>';
				 $ailmentPiece .= '</td>';
				 
				 $colCount = $colCount+1;
				 

			}//end for energetics loop
			
			
			$list .= $ailmentPiece . '</tr></table></td></tr>';  //need to a end row.
			
			  		
  		$list .= '        <tr><td>';
      $list .= '  			<P><INPUT TYPE="SUBMIT" NAME="submit" VALUE="Update"></P>';
  		$list .= '        </td></td>';
      $list .= '  			</FORM>';
  		$list .= '      </table>';
		
		
    $list .= '  		</td>';
    $list .= '  	</tr>';
    $list .= '  </table>';
		
	
  return $list;
}//end editHerb

function updateHerb()
{
    global $title;

		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
		
		$datarray["id"] = $_POST["id"];
		$datarray["herb"] = $_POST["herb"];
		$datarray["description"] = $_POST["description"];
		$datarray["latin_name"] = $_POST["latin_name"];
		$datarray["other_names"] = $_POST["other_names"];
		$datarray["warning"] = $_POST["warning"];
		$datarray["nutritional"] = $_POST["food"];
		
    if (is_array($_POST['properties']))
    {
    		foreach ($_POST['properties'] as $propCount => $propertyID)
    		{
    		    $datarray["property"][$propCount] = $propertyID;
    		}
    }
    
    if (is_array($_POST['energetics']))
    {
    		foreach ($_POST['energetics'] as $enerCount =>  $energeticID)
    		{
    		    $datarray["energetics"][$enerCount] = $energeticID;		
    		}
    }
    
    if (is_array($_POST['ailments']))
    {
    		foreach ($_POST['ailments'] as $ailmentCount => $ailmentID)
    		{
    		    $datarray["ailments"][$ailmentCount] = $ailmentID;
    		}
    }
    
    if (is_array($_POST['bodies']))
    {
    		foreach ($_POST['bodies'] as $bodyCount =>  $bodyID)
    		{
    		    $datarray["bodies"][$bodyCount] = $bodyID;		
    		}
    }
		
		//echo "<pre>";
		//print_r($datarray);
		//echo "</pre>";
		
  	$herbs = new herbs;
  	
		//the save function will return either successful or failed
  	$result = $herbs->update_herb($datarray);
		
	  $title = "<strong>" . $datarray["herb"] . " Update: " . $result . "</strong></br></br>";
		$list = listHerbs();
	  return $list;
}//end updateHerb



function saveHerb()
{
    global $title;

		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";

		$datarray["herb"] = $_POST["herb"];
		$datarray["description"] = $_POST["description"];
		$datarray["latin_name"] = $_POST["latin_name"];
		$datarray["other_names"] = $_POST["other_names"];
		$datarray["warning"] = $_POST["warning"];
		$datarray["nutritional"] = $_POST["food"];
    if (is_array($_POST['properties']))
    {
    		foreach ($_POST['properties'] as $propCount => $propertyID)
    		{
    		    $datarray["property"][$propCount] = $propertyID;
    		}
    }
    
    if (is_array($_POST['energetics']))
    {
    		foreach ($_POST['energetics'] as $enerCount =>  $energeticID)
    		{
    		    $datarray["energetics"][$enerCount] = $energeticID;		
    		}
    }
    
    if (is_array($_POST['ailments']))
    {
    		foreach ($_POST['ailments'] as $ailmentCount => $ailmentID)
    		{
    		    $datarray["ailments"][$ailmentCount] = $ailmentID;
    		}
    }
    
    if (is_array($_POST['bodies']))
    {
    		foreach ($_POST['bodies'] as $bodyCount =>  $bodyID)
    		{
    		    $datarray["bodies"][$bodyCount] = $bodyID;		
    		}
    }
		

		
  	$herbs = new herbs;
  	
		//the save function will return either successful or failed
  	$result = $herbs->save_herb($datarray);
		
	  $title = "<strong>" . $datarray["herb"] . " Update: " . $result . "</strong><br>";
		
		$list = listHerbs();
	  return $list;
}//end saveHerb



?>