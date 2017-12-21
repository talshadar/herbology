(<?php
##########################################################################
#  Herbology
#  herb Page
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################
// Report all PHP errors (see changelog)
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
/*
if (!$_SESSION['conoco']['account']['userID'])
{
	header("Location: login.php");
	exit;
}
*/
include_once('classes/class.herbs.inc.php');
//include_once('classes/class.properties.inc.php');
//include_once('classes/class.energetics.inc.php');
//include_once('classes/class.ailments.inc.php');
//include_once('classes/class.parts.inc.php');
//include_once('classes/class.body.inc.php');

include ('inc/header.inc.php');


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
    //$ailments = new ailments();
    $list = "";

    /*
    foreach ($herbList as $count => $array)
    {
        $newArray[$count] = set_array_names($array,'docDwg');
    }
    unset($docdwgList);
    $docdwgList = $newArray;
    */

    /*
    echo "Herb List 1<pre>";
    print_r($herbList);
    echo "</pre>";
    */
	
    if (!is_null($herbList))
    {
  	foreach ($herbList as $count => $data)
  	{
            $herbInfo[$count]['herbID'] = $data[0];
            $herbInfo[$count]['herb'] = $data[1];
            $herbInfo[$count]['warning'] = $data[5];
            
            $propInfo ="";
/*
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
*/
  	}	
    }

  
    echo "Sorted<pre>";
    print_r($herbInfo);
    echo "</pre>";

    $rowStyle = 'rowoff';
    
    
    
    //now start looping through the users info
    if (!is_null($herbInfo))
    {
  	foreach ($herbInfo as $id => $data)
  	{
            $rowStyle == 'rowon' ? $rowStyle = 'rowoff' : $rowStyle = 'rowon';
            $list .= '<p class="' . $rowStyle . '">';
            $list .= '<a href="list_herbs.php?herb=' . $data['herbID'] . '" target="_blank">';
            $list .= $data['herb']. "&nbsp;";
            $list .= '</a><br/>';
            /*
            if ($data['latin_name'] != "")
            {
             $list .= $data['latin_name']. "<br/>";
            }
            if ($data['other_names'] != "")
            {
               $list .= $data['other_names']. "<br/>";
            }
            */
            if ($data['warning'] != "")
            {
               $list .= "<strong>WARNING: " . $data['warning']. "</strong><br/>";
            }
            /*
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
            */

	    $list .= "</p>";
  	}
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
var property = {};
var propDef = {};

var energetic = {};
var energDef = {};

var ailment = {};
var ailmentDef = {};

<?PHP

		$propertyList = $properties->get_herb_properties($herbID);
		if (!is_null($propertyList))
		{
      	
      	//echo "Properties:<pre>";
       	//print_r($propertyList);
       	//echo "</pre>";

			
			foreach ($propertyList as $propCount => $propData)
			{
			    //$propInfo .= $propData[0] . "(" . $propData[1] . ")";
					
			    $propInfo .= '<a class="propLink" propid="' . $propCount .'">' . $propData[0] . '</a>';
					
					if ($propCount < count($propertyList)-1)
					{
					  $propInfo .= ", ";
					}

			    echo 'property["'. $propCount. '"] = "'.$propData[0].'";';
			    echo 'propDef["'. $propCount. '"] = "'.$propData[1].'";';

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
			    $energInfo .= '<a class="enerLink" enerid="' . $energCount .'">' . $energData[0] . '</a>';
					if ($energCount < count($energeticList)-1)
					{
					  $energInfo .= ", ";
					}
					
			    echo 'energetic["'. $energCount. '"] = "'.$energData[0].'";';
			    echo 'energDef["'. $energCount. '"] = "'.$energData[1].'";';
					
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
			    $ailmentInfo .= '<a class="ailmentLink" ailmentid="' . $ailmentCount .'">' . $ailmentData[0] . '</a>';
					if ($ailmentCount < count($ailmentList)-1)
					{
					  $ailmentInfo .= ", ";
					}
					
			    echo 'ailment["'. $ailmentCount. '"] = "'.$ailmentData[0].'";';
			    echo 'ailmentDef["'. $ailmentCount. '"] = "'.$ailmentData[1].'";';
					
			}
      	
			//echo $energInfo . "<br>";
		}
		$herbInfo['ailments'] = $ailmentInfo;

			
?>

$(document).on("click", ".propLink", function() {
  var id = $(this).attr("propid");
	var defTitle = 'Definition: ' + property[id];
	$("#myModalTitle").html(defTitle);
  $("#myModalContent").html(propDef[id]);
  $("#myModal").modal("show");
});


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
       myModal.style.display = "none";
    }
}

$(document).on("click", ".enerLink", function() {
  var id = $(this).attr("enerid");
	var defEnerTitle = 'Definition: ' + energetic[id];
	$("#myModalTitle").html(defEnerTitle);
  $("#myModalContent").html(energDef[id]);
  $("#myModal").modal("show");
});

$(document).on("click", ".ailmentLink", function() {
  var id = $(this).attr("ailmentid");
	var defailmentTitle = 'Definition: ' + ailment[id];
	$("#myModalTitle").html(defailmentTitle);
  $("#myModalContent").html(ailmentDef[id]);
  $("#myModal").modal("show");
});

</script>
<?PHP
      	


	}

	$rowStyle == 'rowoff';

	//echo "<pre>";
	//print_r($herbInfo);
	//echo "</pre>";
	
  if (!is_null($herbInfo))
  {

	    $list .= '<table border="1" cellpadding="2" cellspacing="0" width="830px">';
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
						
						$carouseSlideBtnCode .= '<li data-target="#myCarousel" data-slide-to="' . $imageCount . '"';
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
				
  		}
			
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
  		$list .= '<tr >'; 
   		$list .= '<td align="left" colspan="2"><strong>Warnings:</strong>';
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
	    $list .= "</table>";
			$list .= "<br/>";
  	
  }
	
	
	return $list;
	
}


?>