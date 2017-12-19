<?php
##########################################################################
#  Herbology
#  Formujla Page
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
include_once('classes/class.herbs.inc.php');
include_once('classes/class.formulas.inc.php');

include ('inc/header.inc.php');

if (!$_GET && !$_POST)
{
  $list = listAllFormulas();
}
else
{
		if ($_GET['formulaID'])
		{
    		$list .= listFormula($_GET['formulaID']);
		}
}


?>

<table border="0" cellpadding="0" cellspacing="0" >
	<tr valign="middle">
		<td align="left"><br>
				<? echo $title; ?>
		</td>
	</tr>
	<tr>
	  <td> <? echo $list; ?> </td>
	</tr>
</table>

<?


# now include the footer
include ('inc/footer.inc.php');


########  FUNCTIONS  #######

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
	
}//end listAllFormulas

function listFormula($formulaD)
{
	$formulas = new formulas;
	$formulaList = $formulas->get_formula($formulaD);
	$herbs = new herbs;
	
	//echo "formulaList List 1<pre>";
 	//print_r($formulaList);
 	//echo "</pre>";
	
	
	
  if (!is_null($formulaList))
	{

			$formulaInfo[$count]['formulaID'] = $formulaList[0][0];
			$formulaInfo[$count]['name'] = $formulaList[0][1];
			$formulaInfo[$count]['description'] = $formulaList[0][2];
			$herbInfo[0] ="";

			
				$herbList = $formulas->get_formula_herbs($formulaList[0][0]);
				if (!is_null($herbList))
				{
        	
        	//echo "Herbs:<pre>";
         	//print_r($herbList);
         	//echo "</pre>";
			
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
var herb = {};
var herbDef = {};

var preparation = {};
var prepDef = {};

<?

					foreach ($herbList as $herbCount => $herbData)
					{
					
					 		$individualHerbInfo = $herbs->get_Herb($herbData[0]);
            	
							//echo "Herbs:<pre>";
             	//print_r($individualHerbInfo);
             	//echo "</pre>";
												
					    //$propInfo .= '<a class="propLink" propid="' . $propCount .'">' . $propData[0] . '</a>';
							$herbInfo[$herbCount]["herb"] = '<a class="herbLink" herbid="' . $herbCount .'">' . $herbData[1] . '</a>';
							$herbInfo[$herbCount]["part"] = $herbData[2];
							$herbInfo[$herbCount]["action"] = $herbData[3];
							
    			    echo 'herb["'. $herbCount. '"] = "'.$herbData[1].'";';
    			    echo 'herbDef["'. $herbCount. '"] = "'.$individualHerbInfo[0][4].'";';		//description					
					}
        	
					//echo "<br>HERBLIST:".$herbInfo . "<br>";
				}// end if is_null(herbList)
				
				$formulaInfo[$count]['herbs'] = $herbInfo;

				$preparationInfo ="";
				
				$preparationList = $formulas->get_formula_preparations($formulaList[0][0]);
				if (!is_null($preparationList))
				{
        	
        	//echo "Preparations:<pre>";
         	//print_r($preparationList);
         	//echo "</pre>";
					
					foreach ($preparationList as $prepCount => $prepData)
					{
					    $preparationInfo .= '<a class="prepLink" prepid="' . $prepCount .'">' . $prepData[0] . '</a>'; // $prepData[0];
							if ($prepCount < count($preparationList)-1)
							{
							  $preparationInfo .= ", ";
							}
    			    echo 'preparation["'. $prepCount. '"] = "'.$prepData[0].'";';
    			    echo 'prepDef["'. $prepCount. '"] = "'.$prepData[1].'";';		//description					
					}
        	
					//echo $preparationInfo . "<br>";
				}
				
				$formulaInfo[$count]['preparations'] = $preparationInfo;
				
?>

$(document).on("click", ".herbLink", function() {
  var id = $(this).attr("herbid");
	var defTitle = 'Definition: ' + herb[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(herbDef[id]);
  $("#myModal").modal("show");
});


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
       myModal.style.display = "none";
    }
}

$(document).on("click", ".prepLink", function() {
  var id = $(this).attr("prepid");
	var defTitle = 'Definition: ' + preparation[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(prepDef[id]);
  $("#myModal").modal("show");
});


</script>

<?

        	//echo "Preparations:<pre>";
         	//print_r($preparationList);
         	//echo "</pre>";


	}//end if is_null(formulaList)

 	//echo "Sorted<pre>";
 	//print_r($formulaInfo);
 	//echo "</pre>";

		
		
	$rowStyle = 'rowoff';
	//now start looping through the users info

  if (!is_null($formulaInfo))
  {
  	foreach ($formulaInfo as $id => $data)
  	{
	    $list .= '<p >';
   		$list .= '<STRONG>' . $data['name']. "</strong>&nbsp;";
			$list .= '<br/>';
			if ($data['description'] != "")
			{
			   $list .= $data['description']. "<br/></br>";
			}			

			
			//herb chunk
			$list .= '<STRONG>Herbs</strong></br>';
  		$list .= '   <table border="1" width="100%">';

			$rowStyle = 'rowoff';

			$herbPiece = '<tr class="' . $rowStyle . '">';
			$herbPiece .= '<td>Herb</td><td align="center">Part(s)</td><td align="right">Action</td></tr>';
			foreach ($data["herbs"] as $herbCount => $herbData)
			{
		   	 $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
			   $herbPiece .= '<tr class="' . $rowStyle . '">';

				 $herbPiece .= '<td >';
				 $herbPiece .=  $herbData["herb"];
				 $herbPiece .= '</td>';
				 $herbPiece .= '<td width="50px" align="center">';
				 $herbPiece .=  $herbData["part"];
				 $herbPiece .= '</td>';
				 $herbPiece .= '<td width="125px" align="right">';
				 $herbPiece .=  $herbData["action"];
				 $herbPiece .= '</td></tr>';
				 
			}//end for properties loop
			
			$list .= $herbPiece . '</table></br>';  //need to a end row.
			
			if ($data['preparations'] != "")
			{
   		   $list .= "Preparations: " . $data['preparations'] . "<br/>";
			}
	    $list .= "</p>";
  	}
  }//end if is_null(formulaInfo)

	
	return $list;
	
}//end listFormula

?>