<?PHP
##########################################################################
#  Herbology
#  herb Page
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################
// Report all PHP errors (see changelog)
error_reporting(E_ALL);

session_start();
/*
if (!$_SESSION['conoco']['account']['userID'])
{
	header("Location: login.php");
	exit;
}
*/
include_once('classes/class.herbs.inc.php');
include_once('classes/class.properties.inc.php');
include_once('classes/class.energetics.inc.php');
include_once('classes/class.ailments.inc.php');
include_once('classes/class.parts.inc.php');
include_once('classes/class.body.inc.php');

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

<?PHP 


if (!$_GET && !$_POST)
{
  $list = listAilments();
}
else
{
  $ailmentID = $_GET['ailment'];
	$list .= listAilment($ailmentID);
}

?>

<table border="0" cellpadding="0" cellspacing="0" width = "80%">
	<tr valign="middle">
		<td align="left"><br>
				<?PHP  echo $title; ?>
		</td>
	</tr>
	<tr>
	  <td> <?PHP  echo $list; ?> </td>
	</tr>
</table>

<?PHP 


# now include the footer
include ('inc/footer.inc.php');


########  FUNCTIONS  #######

function listAilments()
{
	$herbs = new herbs;
	$properties = new properties();
	$energetics = new energetics();
	$ailments = new ailments();
	$ailmentList = $ailments->get_ailments();
	
  if (!is_null($ailmentList))
	{
  	foreach ($ailmentList as $count => $data)
  	{
  			$ailmentInfo[$count]['ailmentID'] = $data[0];
  			$ailmentInfo[$count]['ailment'] = $data[1];
  			$ailmentInfo[$count]['description'] = $data[2];

  	}	
	}

  
 	//echo "<pre>";
 	//print_r($ailmentInfo);
 	//echo "</pre>";
		
	$rowStyle = 'rowoff';
	//now start looping through the users info
  if (!is_null($ailmentInfo))
  {
  	foreach ($ailmentInfo as $id => $data)
  	{
  		$rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
	    $list .= '<p class="' . $rowStyle . '">';
			$list .= '<a href="list_ailments.php?ailment=' . $data['ailmentID'] . '" target="_blank">';
   		$list .= $data['ailment']. "&nbsp;";
			$list .= '</a><br/>';
			if ($data['description'] != "")
			{
   		   $list .= $data['description']. "<br/>";
			}

	    $list .= "</p>";
  	}
  }
	
	
	return $list;
	
}//end listHerbs

function listailment($ailmentID)
{
	$herbs = new herbs;
	$properties = new properties();
	$energetics = new energetics();
	$ailments = new ailments();
	$ailmentList = $ailments->get_ailment($ailmentID);
	
	//echo "Ailment:" . $ailmentID . "<pre>";
 	//print_r($ailmentList);
 	//echo "</pre>";
	
  if (!is_null($ailmentList))
	{
			$ailmentInfo['index'] = $ailmentList[0][0];
			$ailmentInfo['ailment'] = $ailmentList[0][1];
			$ailmentInfo['description'] = $ailmentList[0][2];	

?>

<script>
var herb = {};
var herbDef = {};

<?PHP 

		$herbList = $ailments->get_ailment_herbs($ailmentID);
		if (!is_null($herbList))
		{
      	//echo "Properties:<pre>";
       	//print_r($propertyList);
       	//echo "</pre>";

			foreach ($herbList as $herbCount => $herbData)
			{
			    //$propInfo .= $propData[0] . "(" . $propData[1] . ")";
					
			    $herbInfo .= '<a class="herbLink" herbid="' . $herbData[0] .'">' . $herbData[1] . '</a>';
					
					if ($herbCount < count($herbList)-1)
					{
					  $herbInfo .= ", ";
					}

			    echo 'herb["'. $herbData[0]. '"] = "'.$herbData[1].'";';
			    echo 'herbDef["'. $herbData[0] . '"] = '. json_encode($herbData[2]).';';

			}//end for herb loop

			//echo $propInfo . "<br>";
		}
		$ailmentInfo['herbs'] = $herbInfo;
			
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
};

</script>
<?PHP 
      	
	}

	$rowStyle == 'rowoff';

	//echo "wierd<pre>";
	//print_r($ailmentInfo);
	//echo "</pre>";
	
  if (!is_null($ailmentInfo))
  {

	    $list .= '<table border="1" cellpadding="2" cellspacing="0" width="830px">';
  		$list .= '<tr>';  		
   		$list .= '<td align="left">';
   		$list .= $ailmentInfo['ailment']. "&nbsp;";
   		$list .= "</td>";
   		$list .= "</tr>";

  		$list .= '<tr >'; 
  		$list .= '<td align="left" >';
			$list .= $ailmentInfo['description']. "&nbsp;";
  		$list .= "</td>";
   		$list .= "</tr>";

  		$list .= '<tr >'; 
   		$list .= '<td align="left">';
   		$list .= "Herbs: " . $ailmentInfo['herbs'] . "&nbsp;";
   		$list .= "</td>";
   		$list .= "</tr>";

	    $list .= "</table>";
			$list .= "<br/>";
  	
  }
	
	
	return $list;
	
}


?>