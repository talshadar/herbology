<?php
##########################################################################
#  Herbology
#  herb Page
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################
// Report all PHP errors (see changelog)
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//ini_set('memory_limit', '-1');

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

include('inc/header.inc.php');

$title="";
$list="";

if (!$_GET && !$_POST)
{
    $list = listHerbs();
}
else
{
    $herbID = $_GET['herb'];
    $list .= listHerb($herbID);
}

?>

<table border="0" cellpadding="0" cellspacing="0" width = "80%">
    <tr valign="middle">
        <td align="left"><br>
            <?PHP echo $title; ?>
        </td>
    </tr>
    <tr>
        <td> <?PHP echo $list; ?> </td>
    </tr>
</table>

<?PHP


# now include the footer
include ('inc/footer.inc.php');


########  FUNCTIONS  #######

function listHerbs()
{
    $herbs = new herbs;
    $herbList = $herbs->get_herbs();
    //$properties = new properties();
    //$energetics = new energetics();
    $ailments = new ailments();
    $list = "";

    /*
    echo "Herb List 1<pre>";
    print_r($herbList);
    echo "</pre>";
    */
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

function showAilInfo(str)
{
    if (str == "") {
        document.getElementById("myModalContent").innerHTML = "NOT FOUND";
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
                $("#myModalTitle").html("Definition:");
                $("#myModal").modal("show");
            }
        };
        xmlhttp.open("GET","jsPhpFunctions.php?which=getAilment&id="+str,true);
        xmlhttp.send();
    }
}
</script>
<?PHP    

	
    if (!is_null($herbList))
    {
  	foreach ($herbList as $count => $data)
  	{
            $herbInfo[$count]['herbID'] = $data[0];
            $herbInfo[$count]['herb'] = $data[1];
            $herbInfo[$count]['warning'] = $data[5];

            $ailmentInfo ="";
            $ailmentList = $ailments->get_herb_ailments($data[0]);
            if (!is_null($ailmentList))
            {
                foreach ($ailmentList as $ailmentCount => $ailmentData)
                {
                    //$ailmentInfo .= $ailmentData[0];
                    $ailmentInfo .= '<a class="ailmentLink" onclick="showAilInfo(' . $ailmentData[2] .')"  ailmentid="' . $ailmentCount .'">' . $ailmentData[0] . '</a>';

                    if ($ailmentCount < count($ailmentList)-1)
                    {
                      $ailmentInfo .= ", ";
                    }
                }
            }

            $herbInfo[$count]['ailments'] = $ailmentInfo;
    
            
  	}	
    }

    /*
    echo "Sorted<pre>";
    print_r($herbInfo);
    echo "</pre>";
    */
    
    $rowStyle = 'rowoff';
    
    //now start looping through the herb info
    if (!is_null($herbInfo))
    {
        $list = '<div class="table-responsive">';
        $list = '<table class="table table-striped">';
        $list .= '<tbody>';
        
        
  	foreach ($herbInfo as $id => $data)
  	{
            //$rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
            //$list .= '<p class="' . $rowStyle . '">';
            $list .= '<tr>';
            $list .= '<td>';
            $list .= '<p class="text-info">';
            $list .= '<a href="list_herbs.php?herb=' . $data['herbID'] . '" target="_blank">';
            $list .= $data['herb']. "&nbsp;";
            $list .= '</a></p>';

            if ($data['warning'] != "")
            {
               $list .= "<p class='text-danger'>WARNING: " . $data['warning'] . "</p>";
            }

            if ($data['ailments'] != "")
            {
               $list .= '<p class="text-info"> <strong>Ailments:</strong> ' . $data['ailments'] . "</p>";
            }
            
            $list .= '</td>';
            $list .= '</tr>';
  	}
        
        $list .= '</tbody>';
        $list .= '</table>';
        $list .= '</div>';
    }
    
	
    return $list;
	
}//end listHerbs



function listHerb($herbID)
{
    $herbs = new herbs;
    $herbList = $herbs->get_herb($herbID);
    $properties = new properties();
    $energetics = new energetics();
    $ailments = new ailments();
    $list="";    
    $carouselCode = "";
    $carouselSlideBtnCode = "";
    $carouselImageCode = "";
    $rowStyle="";
    $carouselBtnCode = "";

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
        $herbInfo['nutritional'] = $herbList[0][6];

        $propInfo ="";
		
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

function showPropInfo(str)
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
                $("#myModalTitle").html("Definition:");
                $("#myModal").modal("show");
            }
        };
        xmlhttp.open("GET","jsPhpFunctions.php?which=getProperty&id="+str,true);
        xmlhttp.send();
    }
}

function showEnerInfo(str)
{
    if (str == "") {
        document.getElementById("myModalContent").innerHTML = "NOT FOUND";
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
                $("#myModalTitle").html("Definition:");
                $("#myModal").modal("show");
            }
        };
        xmlhttp.open("GET","jsPhpFunctions.php?which=getEnergetic&id="+str,true);
        xmlhttp.send();
    }
}

function showAilInfo(str)
{
    if (str == "") {
        document.getElementById("myModalContent").innerHTML = "NOT FOUND";
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
                $("#myModalTitle").html("Definition:");
                $("#myModal").modal("show");
            }
        };
        xmlhttp.open("GET","jsPhpFunctions.php?which=getAilment&id="+str,true);
        xmlhttp.send();
    }
}

<?PHP	
        
        $propertyList = $properties->get_herb_properties($herbID);
        if (!is_null($propertyList))
        {
            //$propData[0] = property term
            //$propData[1] = property definition
            //$propData[2] = property id
            
            //echo "Properties:<pre>";
            //print_r($propertyList);
            //echo "</pre>";
			
            foreach ($propertyList as $propCount => $propData)
            {
                //$propInfo .= $propData[0] . "(" . $propData[1] . ")";

                $propInfo .= '<a class="propLink"  onclick="showPropInfo(' . $propData[2] .')"  propid="' . $propCount .'">' . $propData[0] . '</a>';

                if ($propCount < count($propertyList)-1)
                {
                  $propInfo .= ", ";
                }

            }//end for properties loop

            //echo $propInfo . "<br>";
        }
        $herbInfo['properties'] = $propInfo;

        $energInfo ="";

        $energeticList = $energetics->get_herb_energetics($herbID);
        if (!is_null($energeticList))
        {
            foreach ($energeticList as $energCount => $energData)
            {
                $energInfo .= '<a class="enerLink" onclick="showEnerInfo(' . $energData[2] .')"  enerid="' . $energCount .'">' . $energData[0] . '</a>';
                if ($energCount < count($energeticList)-1)
                {
                  $energInfo .= ", ";
                }

            }

            //echo $energInfo . "<br>";
        }
        $herbInfo['energetics'] = $energInfo;

        $ailmentInfo ="";

        $ailmentList = $ailments->get_herb_ailments($herbID);
        if (!is_null($ailmentList))
        {
            foreach ($ailmentList as $ailmentCount => $ailmentData)
            {
                $ailmentInfo .= '<a class="ailmentLink" onclick="showAilInfo(' . $ailmentData[2] .')"  ailmentid="' . $ailmentCount .'">' . $ailmentData[0] . '</a>';
                if ($ailmentCount < count($ailmentList)-1)
                {
                  $ailmentInfo .= ", ";
                }

            }

            //echo $energInfo . "<br>";
        }
        $herbInfo['ailments'] = $ailmentInfo;
		
?> 

</script>
<?PHP
      	


    }//end if is_null(herb_list)

    $rowStyle == 'rowoff';

    //echo "<pre>";
    //print_r($herbInfo);
    //echo "</pre>";

    $list = '<div class="container">';
    
    if (!is_null($herbInfo))
    {
        $list .= '<div class="table-responsive">';
        $list .= '<table class="table">';
        $list .= '<tbody>';
        $list .= '<tr>';  		
        $list .= '<td align="left" width="300">';
        $list .= $herbInfo['herb']. "&nbsp;";
        $list .= "</td>";
        $list .= '<td align="left" rowspan="3">';
			
        /*
        get several images for each as well - new image table for file names
        create small angular object to let user cycle through herb-specific images		
        */

			
        $herbImageList = $herbs->get_herbImages($herbID);
        if (!is_null($herbImageList))
        {
            /*
            echo "Images:<pre>";
            print_r($herbImageList);
            echo "</pre>";
            */

                    
            $carouselCode = '<div id="myCarousel" class="carousel slide" data-ride="carousel" >';
            $carouselCode .= '<!-- Indicators -->';
            $carouselCode .= '<ol class="carousel-indicators">';

            foreach ($herbImageList as $imageCount => $imageData)
            {
                $imageFilename[$imageCount] = $imageData[0];
                $imageDesc[$imageCount] = $imageData[1];

                $carouselSlideBtnCode .= '<li data-target="#myCarousel" data-slide-to="' . $imageCount . '"';
                if ($imageCount == 0) { $carouselBtnCode .= ' class="active"'; };
                $carouselSlideBtnCode .= '></li>';

                $carouselImageCode .= '<div class ="item ';
                if ($imageCount == 0) { $carouselImageCode .= ' active'; }
                $carouselImageCode .= '">';

                $carouselImageCode .= '<a href="images/herbs/' . $imageFilename[$imageCount] . '" target="_blank">';
                $carouselImageCode .= '<img src="images/herbs/400x400/' . $imageFilename[$imageCount] . '" alt="' . $imageDesc[$imageCount] . '" class="carouselImage">';
                $carouselImageCode .= '</a>';
                $carouselImageCode .= '</div>';

            }			
  			
            $carouselCode .= $carouselSlideBtnCode;
            $carouselCode .= '</ol>';
            $carouselCode .= '<!-- Wrapper for slides -->';
            $carouselCode .= '<div class="carousel-inner">';
            $carouselCode .= $carouselImageCode;
            $carouselCode .= '</div>';
            $carouselCode .= '<a class="left carousel-control" href="#myCarousel" data-slide="prev">';
            $carouselCode .= '<span class="glyphicon glyphicon-chevron-left"></span>';
            $carouselCode .= '<span class="sr-only">Previous</span>';
            $carouselCode .= '</a>';
            $carouselCode .= '<a class="right carousel-control" href="#myCarousel" data-slide="next">';
            $carouselCode .= '<span class="glyphicon glyphicon-chevron-right"></span>';
            $carouselCode .= '<span class="sr-only">Next</span>';
            $carouselCode .= '</a>';
            $carouselCode .= '</div>';
				
        }// end if is_null(herblistimages)
			
        $list .= $carouselCode. "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr >'; 
        $list .= '<td align="left">Latin Name: ';
        $list .= $herbInfo['latin_name']. "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr>'; 
        $list .= '<td align="left">Other Names: ';
        $list .= $herbInfo['other_names']. "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr >'; 
        $list .= '<td align="left" colspan="2">';
        $list .= $herbInfo['description']. "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr class="danger">'; 
        $list .= '<td class="danger" align="left" colspan="2"><strong>Warnings:</strong>';
        $list .= $herbInfo['warning']. "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr >'; 
        $list .= '<td align="left" colspan="2">Nutritional: ';
        $list .= $herbInfo['nutritional']. "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr >'; 
        $list .= '<td align="left" colspan="2">';
        $list .= "Properties: " . $herbInfo['properties'] . "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr >'; 
        $list .= '<td align="left" colspan="2">';
        $list .= "Energetics: " . $herbInfo['energetics'] . "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        $list .= '<tr >'; 
        $list .= '<td align="left" colspan="2">';
        $list .= "<strong>Ailments:</strong> " . $herbInfo['ailments'] . "&nbsp;";
        $list .= "</td>";
        $list .= "</tr>";
        
        $list .= '</tbody>';
        $list .= "</table>";
        $list .= "</div";
  	
    }// end if isnull(herblist)

    $list .= '</div>';
	
    return $list;
	
}// end listHerb function
