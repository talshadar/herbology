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
include_once('classes/class.formulas.inc.php');
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
  window.location = "admin_formulas.php?action=list&which=formulas";
}

function addHerb(){

  window.location = "admin_formulas.php?action=add";
	
  //var x;

  //var herbCount=prompt("How many herbs for the new formula?", "4");

  //if (herbCount!=null)
  //{
    //window.location = "admin_formulas.php?action=add&herbCount=" + herbCount;
  //}

}

</script>
 <?php

if (!$_GET && !$_POST)
{
    $list = listAllFormulas();
}
else
{
  
    $action =  $_GET['action'];
    $which = $_GET['which'];
    $id = $_GET['id'];
    $msg = $_GET['msg'];

    //echo "<br>action:".$action;
    //echo "<br>count:".$herbCount;
    //echo "<br>";
	
    if ($action=="list")
    {
        if ($which == "formulas")
        {
            $title = "List Formulas";
            $list .= listAllFormulas();		
        }
    }
    elseif ($action == "edit" && !empty($id) )
    {
        //echo "<br>EDIT:" . $which . "-" . $id;
        if ($which == "formula")
        {
            $title = "Edit Formula: ";
            $list .= editFormula($id);		
        }	
    }
    elseif ($action=='update')
    {
        $list .= updateFormula();
    }
    elseif ($action=='add')
    {
        $list .= addFormulaHerbs();
    }
    elseif ($action=='create')
    {
        $list .= createFormula();
    }
    elseif ($action=='save')
    {
        $result = saveFormula();

        if ($result == "Succeeded")
        {
            $title = "Formula Added";
            $list .= listAllFormulas();
        }
    }

}


 if ( $_GET ){ ?>
 <div class="menu">
   <button onclick="addHerb()">Add New Formula</button>
   <button onclick="listHerbs()">List Formulas</button>
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


function listAllFormulas()
{
    $formulas = new formulas;
    $formulaList = $formulas->get_formulas();
    $herbs = new herbs;
	
    //echo "formulaList List 1<pre>";
    //print_r($formulaList);
    //echo "</pre>";
	
	
    if (!is_null($formulaList))
    {
  	foreach ($formulaList as $count => $data)
  	{
            $formulaInfo[$count]['formulaID'] = $data[0];
            $formulaInfo[$count]['name'] = $data[1];
            $formulaInfo[$count]['description'] = $data[2];
            $herbInfo ="";
				
            $herbList = $formulas->get_formula_herbs($data[0]);
            if (!is_null($herbList))
            {
        	
        	//echo "Herbs:<pre>";
         	//print_r($herbList);
         	//echo "</pre>";
					
                foreach ($herbList as $herbCount => $herbData)
                {
                    $herbInfo .= $herbData[1];  //herbData[0] = herbID, herbData[1] = herb name
                    if ($herbCount < count($herbList)-1)
                    {
                        $herbInfo .= ", ";
                    }
                }
        	
                //echo "<br>HERBLIST:".$herbInfo . "<br>";
            }//end if is_null(herbLIst)
				
            $formulaInfo[$count]['herbs'] = $herbInfo;

            $preparationInfo ="";

            $preparationList = $formulas->get_formula_preparations($data[0]);
            if (!is_null($preparationList))
            {
        	
        	//echo "Preparations:<pre>";
         	//print_r($preparationList);
         	//echo "</pre>";
					
                foreach ($preparationList as $prepCount => $prepData)
                {
                    $preparationInfo .= $prepData[0];
                    if ($prepCount < count($preparationList)-1)
                    {
                      $preparationInfo .= ", ";
                    }
                }
        	
                //echo $energInfo . "<br>";
            }
				
            $formulaInfo[$count]['preparations'] = $preparationInfo;
				
  	}	//end for each FormulaList
    }//end if is_null(formulaLIst)

  
    //echo "Sorted<pre>";
    //print_r($formulaInfo);
    //echo "</pre>";
		
		
    $rowStyle = 'rowoff';
    //now start looping through the users info
    if (!is_null($formulaInfo))
    {
  	foreach ($formulaInfo as $id => $data)
  	{
            $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
	    $list .= '<p class="' . $rowStyle . '">';
            $list .= '<a href="list_formulas.php?formulaID=' . $data['formulaID'] . '" target="_blank">';
            $list .= $data['name']. "&nbsp;";
            $list .= '</a><br/>';
            if ($data['description'] != "")
            {
               $list .= $data['description']. "<br/>";
            }			
            if ($data['herbs'] != "")
            {
                $list .= "Herbs: " . $data['herbs'] . "<br/>";
            }
            if ($data['preparations'] != "")
            {
                $list .= "Preparations: " . $data['preparations'] . "<br/>";
            }
	    $list .= "</p>";
  	}
    }
    return $list;

}//end listFormulas


function editFormula($formulaID)
{
    $list = "edit";
    return $list;

}//end editFormula

function addFormulaHerbs()
{
    //$list = "ADD NEW" . $herbCount . "</br>";
	 
    global $title;

    $formulas = new formulas;

    $herbs = new herbs;

    $allHerbs = $herbs->get_herbs();

    //echo "<pre>";
    //print_r($allHerbs);

    //echo "</pre>";
		
?>

<script>
var herb = {};
var herbDef = {};

<?php

    foreach ($allHerbs as $herbCount => $herbInfo)
    {
        $herbID = $herbInfo[0];
        //echo "</br>herbID:" . $herbID;
        //echo $herbInfo[$herbCount][4];
        //print_r($herbInfo);

        echo 'herb["' . $herbID . '"] = "' . $herbInfo[1] . '";';
        //echo 'herbDef["' . $herbID . '"] = "' . $herbInfo[$herbCount][4] . ": ". $herbInfo[$herbCount][4] . "<br>Properties:" . $propInfo . "<br>Energetics:" . $energInfo . '";';
        echo 'herbDef["' . $herbID . '"] = ' .  json_encode( ' ' .$herbInfo[4]) . ';';
			

    }//end foreach Herb
			
?>
$(document).on("click", ".herbLink", function() {
  var id = $(this).attr("herbid");
	var defHerbTitle = 'Definition: ' + herb[id];
	$("#myModalTitle").html(defHerbTitle);
  $("#myModalContent").html(herbDef[id]);
  $("#myModal").modal("show");
});

$(document).on("click", ".formulaHerb", function() {
    if (!$(this).is(':checked')) {
        return confirm("Are you sure you want to remove " + herb[$(this).attr("value")] + " from the formula?");
    }
		//else
		//{
		// 		return confirm("Adding " + herb[$(this).attr("value")] + " to the formula.");
		//}
		
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
       myModalProp.style.display = "none";
    }
}


</script>
<?php	
		
    $list = '';
    $list .= '  <table border="0" cellpadding="0" cellspacing="0" width = "80%">';
    $list .= '  	<tr valign="middle">';
    $list .= '  		<td align="left">';

    $list .= '      <table border="0" cellpadding="0" cellspacing="0" width = "100%">';
    $list .= '  			<FORM METHOD="POST" ACTION="admin_formulas.php?action=create">';
			
    $colCount = 0;

    $list .= '<tr><td colspan="2"><STRONG>Select Herbs For Formula</strong></td></tr>';
    $list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

    $rowStyle = 'rowoff';
    //allHerbs
    // [0] herb_index
    // [1] herb
    // [4] description

    $propPiece = '<tr class="' . $rowStyle . '">';
    foreach ($allHerbs as $herbCount => $herbData)
    {
        if ($colCount < 10)
        {
            $herbPiece .= '<td >';
        }
        else
        {
            $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
            $herbPiece .= '</tr><tr class="' . $rowStyle . '">';
            $herbPiece .= '<td width="105px">';
            $colCount = 0;
        }
        $herbPiece .= '<input class="formulaHerb" type="checkbox" name="herbs[]" value="'.$herbData[0] . '"';
        $herbPiece .= ' >&nbsp;';
        $herbPiece .=  '<a class="herbLink" herbid="' . $herbData[0] .'">' . $herbData[1] . '</a><br>';
        $herbPiece .= '</td>';

        $colCount = $colCount+1;

    }//end for herbs loop
			
    $list .= $herbPiece . '</tr></table></td></tr>';  //need to a end row.
			
    $list .= '        <tr><td>';
    $list .= '  			<P><INPUT TYPE="SUBMIT" NAME="submit" VALUE="create"></P>';
    $list .= '        </td></td>';
    $list .= '  			</FORM>';
    $list .= '      </table>';
		
		
    $list .= '  		</td>';
    $list .= '  	</tr>';
    $list .= '  </table>';
	 
    return $list;

}   //end addFormulaHerbs

function createFormula()
{

    //echo "<pre>";
    //print_r($_POST);
    //echo "<pre>";
	
    //herbCount - used to determine how many herb(sections) to us for the formula
    // - select herb to fill text box, textbox for Part, drop down for Actions
    //formula name, description
    //formula_preparations - check box section (similar to herb energetics etc)
    //
	 
    global $title;

    $formulas = new formulas;
    $herbs = new herbs;
		
    $preparations = $formulas->get_preparations();
		
    //echo "<pre>";
    //print_r($preparations);
    //echo "</pre>";		

?>

<script>
var preparation = {};
var prepDef = {};

<?php

    foreach ($preparations as $prepCount => $prepInfo)
    {			
        echo 'preparation["' . $prepInfo[0] . '"] = "' . $prepInfo[1] . '";';
        echo 'prepDef["' . $prepInfo[0] . '"] = ' .  json_encode( ' ' .$prepInfo[2]) . ';';
    }//end foreach Herb
			
?>
$(document).on("click", ".prepLink", function() {
  var id = $(this).attr("prepid");
	var defprepTitle = 'Definition: ' + preparation[id];
	$("#myModalTitle").html(defprepTitle);
  $("#myModalContent").html(prepDef[id]);
  $("#myModal").modal("show");
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
       myModalProp.style.display = "none";
    }
}

</script>
<?php	

		
    $list = '';
    $list .= '  <table border="0" cellpadding="0" cellspacing="0" width = "80%">';
    $list .= '  	<tr valign="middle">';
    $list .= '  		<td align="left">';
		 
    $list .= '      <table border="0" cellpadding="0" cellspacing="0" width = "100%">';
    $list .= '  			<FORM METHOD="POST" ACTION="admin_formulas.php?action=save">';

    $list .= '        <td>';
    $list .= '  			<P><STRONG>Formula:</STRONG><BR>';
    $list .= '  			<INPUT TYPE="text" SIZE="40" NAME="formula" value=""></p>';
    $list .= '        </td></tr>';

    $list .= '        <tr><td >';
    $list .= '  			<P><STRONG>Description:</STRONG><BR>';
    $list .= '  			<textarea rows="6" cols="80" NAME="description"></textarea></p>';
    $list .= '        </td></tr>';
  		

    $list .= '        <tr><td >';
    foreach ($preparations as $prepCount => $preparationData)
    { 
        $prepPiece .= '<input class="formulaPrep" type="checkbox" name="preparations[]" value="'.$preparationData[0] . '"';
        $prepPiece .= ' >&nbsp;';
        $prepPiece .=  '<a class="prepLink" prepid="' . $preparationData[0] .'">' . $preparationData[1] . '</a>&nbsp;&nbsp;&nbsp;';
    }
    $prepPiece .= '</td>';
    $element .= $prepPiece;
		 
    $list .= $element;
    $list .= '        </td></tr>';
			
    $list .= '<tr><td colspan="2"><STRONG>Herbs</strong></td></tr>';
    $list .= '        <tr ><td colspan="2"><table border="0" width="100%">';

    $rowStyle = 'rowoff';

    /*
    [herbs] => Array
        (
            [0] => 7
            [1] => 12
            [2] => 21
            [3] => 23
            [4] => 34
        )
			*/
			
    $actions = $formulas->get_actions();
    //echo "<pre>";
    //print_r($actions);
    //echo "</pre>";

    $herbPiece .= '<tr class="titleBlock">';
    $herbPiece .= '<td >HERB';
    $herbPiece .= '</td>';
    $herbPiece .= '<td >PART';
    $herbPiece .= '</td>';
    $herbPiece .= '<td >ACTION';
    $herbPiece .= '</td>';
    $herbPiece .= '</tr>';
			
    foreach ($_POST['herbs'] as $herbCount => $herbID)
    {
        $herbInfo = $herbs->get_herb($herbID);
        //echo "<pre>";
        //print_r($herbInfo);
        //echo "</pre>";
				 
        $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
        $herbPiece .= '<tr class="' . $rowStyle . '">';
        $herbPiece .= '<td>' . $herbInfo[0][1];
        $herbPiece .= '</td>';
        $herbPiece .= '<td>';
        $herbPiece .= '<INPUT TYPE="text" SIZE="10" NAME="part[' . $herbInfo[0][0] . ']" value="">';
        $herbPiece .= '</td>';
        $herbPiece .= '<td>';
				 
        $element = '<select name="action[' . $herbInfo[0][0] . ']" >';
        foreach ($actions as $actionCount => $actionData)
        { 
            $sel_link .= '<option value="' . $actionData[0] . '"';
            $sel_link .= '>' . $actionData[1] . '</option>' . "\n";
        } 
        $element .= $sel_link;
        $element .= '</select>';
				 
        $herbPiece .= $element;
        $herbPiece .= '</td></tr>';

        $sel_link = '';

    }//end for herbs loop
			
    $list .= $herbPiece . '</tr></table></td></tr>';  //need to a end row.
			
    $list .= '        <tr><td>';
    $list .= '  			<P><INPUT TYPE="SUBMIT" NAME="submit" VALUE="save"></P>';
    $list .= '        </td></td>';
    $list .= '  			</FORM>';
    $list .= '      </table>';
		
    $list .= '  		</td>';
    $list .= '  	</tr>';
    $list .= '  </table>';
	 
    return $list;

}//end createFormula

function saveFormula()
{
    //echo "<pre>";
    //print_r($_POST);
    //echo "<pre>";

    $formula["Name"] = $_POST["formula"];
    $formula["Description"] = $_POST["description"];
    $formulaHerbs[]="";
    $formulaHerbCount = 0;
    foreach ($_POST["part"] as $herbID => $part)
    {
        $formulaHerbs[$formulaHerbCount]["herbID"] = $herbID;
        $formulaHerbs[$formulaHerbCount]["partAmount"] = $part;
        $formulaHerbs[$formulaHerbCount]["actionID"] = $_POST["action"][$herbID];
        $formulaHerbCount += 1;
    }
	
    $formula["formulaHerbs"] = $formulaHerbs;
    $formula["preparations"] = $_POST["preparations"];
	
    $formulas = new formulas;
  
    //echo "<pre>";
    //print_r($formula);
    //echo "</pre>";
	
	
    //the save function will return either successful or failed
    $result = $formulas->save_formula($formula);
	
    return $result;

}//end saveFormula
