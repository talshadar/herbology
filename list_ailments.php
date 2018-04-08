<?PHP
##########################################################################
#  Herbology
#  herb Page
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

$list = "";

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
<div class="container">
    <?PHP echo $list; ?>
</div>

<?PHP

# now include the footer
include ('inc/footer.inc.php');


########  FUNCTIONS  #######

function listAilments()
{
    $list = "";
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
    
    $list = '<table class="table table-striped">';
    $list .= '<tbody>';
    //now start looping through the ailments
    if (!is_null($ailmentInfo))
    {
        $count = 1;
  	foreach ($ailmentInfo as $id => $data)
  	{

            switch ($count)
            {
                case 1:
                    $list .= '<tr>';
                    $list .= '<td>';
                    $list .= '<p class="text-info">';
                    $list .= '<a href="list_ailments.php?ailment=' . $data['ailmentID'] . '" >';
                    $list .= $data['ailment']. "&nbsp;";
                    $list .= '</a></p>';
                    $list .= '</td>';                    
                    break;
                case 2:
                    $list .= '<td>';
                    $list .= '<p class="text-info">';
                    $list .= '<a href="list_ailments.php?ailment=' . $data['ailmentID'] . '" >';
                    $list .= $data['ailment']. "&nbsp;";
                    $list .= '</a></p>';
                    $list .= '</td>';
                    break;
                case 3:
                    $list .= '<td>';
                    $list .= '<p class="text-info">';
                    $list .= '<a href="list_ailments.php?ailment=' . $data['ailmentID'] . '" >';
                    $list .= $data['ailment']. "&nbsp;";
                    $list .= '</a></p>';
                    $list .= '</td>';
                    break;
                case 4:
                    $list .= '<td>';
                    $list .= '<p class="text-info">';
                    $list .= '<a href="list_ailments.php?ailment=' . $data['ailmentID'] . '" >';
                    $list .= $data['ailment']. "&nbsp;";
                    $list .= '</a></p>';
                    $list .= '</td>';
                    break;
                case 5:
                    $list .= '<td>';
                    $list .= '<p class="text-info">';
                    $list .= '<a href="list_ailments.php?ailment=' . $data['ailmentID'] . '" >';
                    $list .= $data['ailment']. "&nbsp;";
                    $list .= '</a></p>';
                    $list .= '</td>';
                    $list .= '</tr>';
                    $count = 0;
                    break;
                
            }
            $count++;

  	}
        
        $list .= '</tbody>';
        $list .= '</table>';
    }
	
	
    return $list;
	
}//end listAilments

function listailment($ailmentID)
{
    $herbs = new herbs;
    $ailments = new ailments();
    $ailmentList = $ailments->get_ailment($ailmentID);
    $herbInfo = "";
    $list="";    

    //echo "Ailment:" . $ailmentID . "<pre>";
    //print_r($ailmentList);
    //echo "</pre>";
    
?>

<!-- The Modal -->
<div id="myModal" class="modal fade">
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

function showHerbInfo(str)
{
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "NOT FOUND";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("myModalContent").innerHTML = this.responseText;
                $("#myModalTitle").html("Herb Information:");
                $("#myModal").modal("show");
            }
        };
        xmlhttp.open("GET","jsPhpFunctions.php?which=getHerb&id="+str,true);
        xmlhttp.send();
    }
}

</script>
<?PHP	

	
    if (!is_null($ailmentList))
    {
        $ailmentInfo['index'] = $ailmentList[0][0];
        $ailmentInfo['ailment'] = $ailmentList[0][1];
        $ailmentInfo['description'] = $ailmentList[0][2];

        $herbList = $ailments->get_ailment_herbs($ailmentID);
        
        if (!is_null($herbList))
        {

            foreach ($herbList as $herbCount => $herbData)
            {
                //$propInfo .= $propData[0] . "(" . $propData[1] . ")";

               //$herbInfo .= '<a class="herbLink" herbid="' . $herbData[0] .'">' . $herbData[1] . '</a>';
                $herbInfo .= '<a class="herbLink"  onclick="showHerbInfo(' . $herbData[0] .')"  herbid="' . $herbData[0] .'">' . $herbData[1] . '</a>';

                if ($herbCount < count($herbList)-1)
                {
                    $herbInfo .= ", ";
                }

            }//end for herb loop

        }
        $ailmentInfo['herbs'] = $herbInfo;

      	
    }

    $list = '<div class="container">';
    	
    if (!is_null($ailmentInfo))
    {
        $list .= '<table class="table">';
        $list .= '<tbody>';
        $list .= '<tr>';  		
        $list .= '<td align="left" width="50%">';
        $list .= $ailmentInfo['ailment']. "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr >'; 
        $list .= '<td align="left">';
        $list .= "Herbs: " . $ailmentInfo['herbs'] . "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '</tbody>';
        $list .= "</table>";
  	
    }

    $list .= '</div>';
    
    return $list;
	
}


?>