<?php
##########################################################################
#  herbology
#  admin page
#
#  Matthew Bryan
#  nov 10, 2017
##########################################################################
// Report all PHP errors (see changelog)
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
session_start();

if (($_SESSION['access'] != "granted"))
{
    header("Location: login.php");
    exit;
}

include_once('classes/class.herbs.inc.php');
include_once('classes/class.properties.inc.php');
include_once('classes/class.energetics.inc.php');
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



</script>
 <?php

if (!$_GET && !$_POST)
{
    $list = listHerbs();
}
else
{
  
    $action =  $_GET['action'];
    $which = $_GET['which'];
    $id = $_GET['id'];
    $msg = $_GET['msg'];

    if ($action=="list")
    {
        if ($which == "herbs")
        {
            $title = "List Herbs";
            $list .= listHerbs();		
        }
        elseif ($which == "properties")
        {
            $title = "List Properties";
            $list .= listProperties();		
        }
        elseif ($which == "energetics")
        {
            $title = "List Energetics";
            $list .= listEnergetics();		
        }
        elseif ($which == "ailments")
        {
            $title = "List Ailments";
            $list .= listAilments();		
        }
        elseif ($which == "formulas")
        {
            $title = "List Formulas";
            $list .= listFormulas();		
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
        elseif ($which == "property")
        {
            $title = "Edit Property: ";
            $list .= editProperty($id);		
        }
        elseif ($which == "energetic")
        {
            $title = "Edit Energetic: ";
            $list .= editEnergetics($id);		
        }
        elseif ($which == "ailment")
        {
            $title = "Edit Ailment: ";
            $list .= editAilments($id);		
        }
        elseif ($which == "formula")
        {
            $title = "Edit Formula: ";
            $list .= editFormula($id);		
        }	
    }
    elseif ($action=='update')
    {
        $list .= saveHerb();
        //$list .= listHerbs();
    }
    elseif ($action=='saveNewHerb' && $_POST['save'])
    {
        //view this account
        $title = "Herb Added";
        $list .= listHerbs();
    }
}


 if ( $_GET ){ ?>
 <div class="backBtn">
   <button onclick="goBack()">Go Back</button>
 </div>
<?php } ?> 
  <table border="0" cellpadding="0" cellspacing="0" width = "95%">
		<TR>
		  <td> <?php echo $title; ?>
			</td>
		</tr>
  	<tr >
  		<td >
			   <?php echo $list; ?>
  		</td>
  	</tr>
  </table>
  
  <?php

  # now include the footer
  include ('inc/footer.inc.php');

	
#################  FUNCTIONS  ##############

function listHerbs()
{
    $herbs = new herbs;
    $herbList = $herbs->get_herbs();
    $properties = new properties();
    $energetics = new energetics();

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
	    $list .= "</p>";
  	}
    }

    return $list;
	
}//end listHerbs

function listProperties()
{
    $properties = new properties();
    $propertyList = $properties->get_properties();

    if (!is_null($propertyList))
    {
  	foreach ($propertyList as $count => $data)
  	{
            foreach ($propertyList as $propCount => $propData)
            {
                $propInfo[$propCount]['propertiesID'] = $propData[0];
                $propInfo[$propCount]['property'] = $propData[1];
                $propInfo[$propCount]['definition'] = $propData[2];				
            }
  	}	
    }

    $rowStyle == 'rowoff';
    if (!is_null($propInfo))
    {
  	foreach ($propInfo as $id => $data)
  	{
            $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
	    $list .= '<p class="' . $rowStyle . '">';
            $list .= '<a href="admin_herbs.php?action=edit&which=property&id=' . $data['propertiesID'] . '" >';
            $list .= $data['property']. "&nbsp;";
            $list .= '</a><br/>';	
            $list .= "Definition: " . $data['definition'] . "<br/>";
	    $list .= "</p>";
  	}
    }
	
    return $list;
	
}//end list properties

function listEnergetics()
{
}//end listEnergetics

function listAilments()
{
}//end listAilments

function listFormulas()
{
}//end listFormulas

function editHerb($herbID)
{
    global $title;
    $herbs = new herbs;
    $properties = new properties();
    $energetics = new energetics();

    $herbList = $herbs->get_herb($herbID);

    /*
    echo "herbs:<pre>";
    print_r($herbList);
    echo "</pre>";
    */
	
    if (!is_null($herbList))
    {
        $herbInfo['index'] = $herbList[0][0];
        $herbInfo['herb'] = $herbList[0][1];
        $herbInfo['latin_name'] = $herbList[0][2];
        $herbInfo['other_names'] = $herbList[0][3];
        $herbInfo['description'] = $herbList[0][4];
        $herbInfo['warning'] = $herbList[0][5];
        $herbInfo['nutritional'] = "food or nutrional use";
    }//end ifNull check for $herbList	
    $title .= $herbInfo['herb'];
    $propertyList[0] = "";
    $propertyList = $properties->get_herb_properties($herbID);
    $allProperties = $properties->get_properties();
			
?>

<script>
var property = {};
var propDef = {};

var energetic = {};
var energDef = {};

<?php		

    //must set propInfo and enerInfo as arrays in case they have no data
    $propInfo[0] = "";
    $enerInfo[0] = "";


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

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
       myModal.style.display = "none";
    }
}



</script>
<?php
		/*
    echo "<pre>";
    print_r($herbInfo);
    print_r($propertyList);
    print_r($energeticList);
    print_r($allProperties);
    print_r($allEnergetics);
    echo "</pre>";
    */
		
		
    $list = '';
    $list .= '  <table border="0" cellpadding="0" cellspacing="0" width = "800px">';
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
    $list .= '  			<textarea rows="3" cols="130" NAME="food">' . $herbInfo['food'] . '</textarea></p>';
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
           $propPiece .= '<td width="105px">';
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

function editProperty($propertyID)
{
    $list = "edit";
    return $list;

}//end editProperty

function editEnergetic($energeticID)
{
    $list = "edit";
    return $list;

}//end editEnergetic

function editAilment($ailmentID)
{
    $list = "edit";
    return $list;

}//end editAilment

function editFormula($formulaID)
{
    $list = "edit";
    return $list;

}//end editFormula

function saveHerb()
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
    foreach ($_POST['properties'] as $propCount => $propertyID)
    {
        $datarray["property"][$propCount] = $propertyID;
    }

    foreach ($_POST['energetics'] as $enerCount =>  $energeticID)
    {
        $datarray["energetics"][$enerCount] = $energeticID;		
    }

    //echo "<pre>";
    //print_r($datarray);
    //echo "</pre>";
		
    $herbs = new herbs;
  	
    //the save function will return either successful or failed
    $result = $herbs->update_herb($datarray);

    $title = "<strong>" . $datarray["herb"] . " Update: " . $result . "</strong><br>";
    $list = listHerbs();
    return $list;
}//end saveHerb

