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

$list = '';
$title="";

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

 echo $list;


# now include the footer
include ('inc/footer.inc.php');


########  FUNCTIONS  #######

function listAllFormulas()
{
    $formulas = new formulas;
    $formulaList = $formulas->get_formulas();
    $herbs = new herbs;
    $list = '';
    
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
                    $preparationInfo .= $prepData[1];
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
		
    if (!is_null($formulaInfo))
    {
        $list = '<table class="table table-striped">';
        $list .= '<tbody>';

        foreach ($formulaInfo as $id => $data)
  	{
            $list .= '<tr>';
            $list .= '<td>';
	    $list .= '<p class="text-primary">';
            $list .= '<a href="list_formulas.php?formulaID=' . $data['formulaID'] . '" >';
            $list .= $data['name']. "&nbsp;";
            $list .= '</a></p>';
            if ($data['description'] != "")
            {
               $list .= '<p class="text-success">' . $data['description'] . "</p>";
            }			
            if ($data['herbs'] != "")
            {
                $list .= '<p class="text-info">Herbs: ' . $data['herbs'] . "</p>";
            }
            if ($data['preparations'] != "")
            {
                $list .= '<p class="text-secondary">Preparations: ' . $data['preparations'] . "</p>";
            }
	    $list .= "</p>";
            $list .= '</td>';
            $list .= '</tr>';            
  	}
        
        $list .= '</tbody>';
        $list .= '</table>';        
    }
	
	
    return $list;
	
}//end listAllFormulas

function listFormula($formulaD)
{
    $formulas = new formulas;
    $formulaList = $formulas->get_formula($formulaD);
    $herbs = new herbs;
    $list="";
    $data="";


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
function showPrepInfo(str)
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
                $("#myModalTitle").html("Preparation Description:");
                $("#myModal").modal("show");
            }
        };
        xmlhttp.open("GET","jsPhpFunctions.php?which=getPreparation&id="+str,true);
        xmlhttp.send();
    }
}

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

<?php
    
    //echo "formulaList List 1<pre>";
    //print_r($formulaList);
    //echo "</pre>";
	
    if (!is_null($formulaList))
    {

        $formulaInfo['formulaID'] = $formulaList[0][0];
        $formulaInfo['name'] = $formulaList[0][1];
        $formulaInfo['description'] = json_encode($formulaList[0][2]);
        $herbInfo[0][0] = "";

        $herbList = $formulas->get_formula_herbs($formulaList[0][0]);
        if (!is_null($herbList))
        {
        	
            //echo "Herbs:<pre>";
            //print_r($herbList);
            //echo "</pre>";

            foreach ($herbList as $herbCount => $herbData)
            {
                //$propInfo .= $propData[0] . "(" . $propData[1] . ")";

               //$herbInfo .= '<a class="herbLink" herbid="' . $herbData[0] .'">' . $herbData[1] . '</a>';
                //$herbInfo .= '<a class="herbLink"  onclick="showHerbInfo(' . $herbData[0] .')"  herbid="' . $herbData[0] .'">' . $herbData[1] . '</a>';

                $herbInfo[$herbCount]["herb"] = '<a class="herbLink"  onclick="showHerbInfo(' . $herbData[0] .')"  herbid="' . $herbData[0] .'">' . $herbData[1] . '</a>';
                $herbInfo[$herbCount]["part"] = $herbData[2];
                $herbInfo[$herbCount]["action"] = $herbData[3];

            }//end for herb loop
	
            //echo "<br>HERBLIST:".$herbInfo . "<br>";
        }// end if is_null(herbList)
				
        $formulaInfo['herbs'] = $herbInfo;

        $preparationInfo ="";

        $preparationList = $formulas->get_formula_preparations($formulaList[0][0]);
        if (!is_null($preparationList))
        {
        	
            //echo "Preparations:<pre>";
            //print_r($preparationList);
            //echo "</pre>";

            foreach ($preparationList as $prepCount => $prepData)
            {
                $preparationInfo .= '<a class="prepLink"  onclick="showPrepInfo(' . $prepData[0] .')" ">' . $prepData[1] . '</a>'; // $prepData[0];
                if ($prepCount < count($preparationList)-1)
                {
                    $preparationInfo .= ", ";
                }
					
            }

            //echo $preparationInfo . "<br>";
        }
				
        $formulaInfo['preparations'] = $preparationInfo;

        //echo "Preparations:<pre>";
        //print_r($preparationList);
        //echo "</pre>";


    }//end if is_null(formulaList)

    //echo "Sorted<pre>";
    //print_r($formulaInfo);
    //echo "</pre>";

    if (!is_null($formulaInfo))
    {
        $list .= '<p >';
        $list .= '<STRONG>' . $formulaInfo['name']. "</strong>&nbsp;";
        $list .= '<br/>';
        if ($formulaInfo['description'] != "")
        {
           $list .= $formulaInfo['description']. "</p>";
        }

        //herb chunk
        $list .= '<table class="table table-striped">';
        $list .= '<tbody>';

        $list .= '<thead class="thead-light">';
        $list .= '<tr>';
        $list .= '<th>Herb</th>';
        $list .= '<th>Part(s)</th>';
        $list .= '<th>Action</th>';
        $list .= '</tr>';
        $list .= '</thead>';
            
        foreach ($formulaInfo["herbs"] as $herbCount => $herbData)
        {
            $list .= '<tr>';

            $list .= '<td >';
            $list .=  $herbData["herb"];
            $list .= '</td>';
            $list .= '<td>';
            $list .=  $herbData["part"];
            $list .= '</td>';
            $list .= '<td >';
            $list .=  $herbData["action"];
            $list .= '</td></tr>';

        }//end for properties loop
        
        $list .= '</tbody>';
        $list .= '</table>';  //need to a end row.

        if ($formulaInfo['preparations'] != "")
        {
            $list .= "Preparations: " . $formulaInfo['preparations'] ;
        }

        $list .= "</p>";
 
    }//end if is_null(formulaInfo)
	
    return $list;
	
}//end listFormula
