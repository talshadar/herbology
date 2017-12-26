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

function admin(){
  window.location = "admin.php";
}

</script>
 <?php

if (!$_GET && !$_POST)
{
  
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

    if ($action=="addImages")
    {
        $title = "Images Saved";			
        $list .= saveImages();
    }

}

/*
########   option for simple browse and preview of image

  <input multiple="1" onchange="readURL(this);" id="uploadedImages" name="pictures[]" type="file">
  <div id ="up_images"></div>

<script type="text/javascript">

  var readURL = function(input) {
      $('#up_images').empty();   
      var number = 0;
      $.each(input.files, function(value) {
          var reader = new FileReader();
          reader.onload = function (e) {
              var id = (new Date).getTime();
              number++;
              $('#up_images').prepend('<img id='+id+' src='+e.target.result+' width="100px" height="100px" data-index='+number+' onclick="removePreviewImage('+id+')"/>')
          };
          reader.readAsDataURL(input.files[value]);
          }); 
    }

</script>

  

*/



 if ( $_GET ){ 

?>
 <div class="menu">
   <button onclick="admin()">Admin</button>
 </div>
<?php 

 } 
 
 ?> 

<script type="text/javascript">

  var readURL = function(input) {
      $('#up_images').empty();   
      var number = 0;
      $.each(input.files, function(value) {
          var reader = new FileReader();
          reader.onload = function (e) {
              var id = (new Date).getTime();
              number++;
              $('#up_images').prepend('<img id='+id+' src='+e.target.result+' width="100px" height="100px" data-index='+number+' onclick="removePreviewImage('+id+')"/>')
          };
          reader.readAsDataURL(input.files[value]);
          }); 
    }

</script>

<h1>Select Herb to upload images for</h1>
<FORM METHOD="POST" ACTION="admin_images.php?action=addImages" enctype="multipart/form-data">
			  
<?php

$herbs = new herbs;
$herbList = $herbs->get_herbs();

//echo "herbs:<pre>";
//print_r($herbList);
//echo "</pre>";
	
foreach ($herbList as $count => $data)
{
    $herbInfo[$data[0]] = $data[1];
} 
    //echo "herbs:<pre>";
    //print_r($herbInfo);
    //echo "</pre>";
		
$element = '<select name="herb" >';
  
foreach($herbInfo as $herbID => $herbName)
{
    $sel_link .= '<option value="' . $herbID . '"';
    $sel_link .= '>' . $herbName . '</option>' . "\n";
}
$element .= $sel_link;
$element .= '</select>';
echo "HERB:" . $element . "</br></br>";

?>
				
    <input multiple="1" onchange="readURL(this);" id="picUpload" name="picUpload[]" type="file" maxlength="120px">    
    <div id ="up_images"></div></br>
     <INPUT TYPE="SUBMIT" NAME="submit" VALUE="Update">
  </FORM>
	
<?php

				
# now include the footer
include ('inc/footer.inc.php');	
#################  FUNCTIONS  ##############

function saveImages()
{
    global $title;
		
    $herbs = new herbs;

    //echo "<pre>";
    //print_r($_POST);
    //print_r($_FILES);
    //echo "</pre>";

    $datarray["herb"] = $_POST["herb"];

    //get herbName for filename
    $herbInfo = $herbs->get_herb($datarray["herb"]);
    //echo "<pre>";
    //print_r($herbInfo);
    //echo "</pre>";
    $herbName = str_replace(" ", "_", $herbInfo[0][1]);
    //echo "<br/>" . $herbName;
    $datarray["herbName"] = $herbName;

    $herbImages = $herbs->get_herbImages($datarray["herb"]);
    $imageCount = count($herbImages);
    $imageCount++;

    $target_path = "images/herbs/";
    //need to create the dir
    if (!is_dir($target_path))
    {
        mkdir($target_path);
    }
    $target_path = "images/herbs/";
    $target_path_thm = "images/herbs/400x400/";
    //need to create the dir
    if (!is_dir($target_path))
    {
        mkdir($target_path);
    }

    foreach ($_FILES['picUpload']['name'] as $fileCount => $fileInfo)
    {
        //echo "<br>file:" . $_FILES['picUpload']["name"][$fileCount];
        //echo "<br>tmp_name:" . $_FILES['picUpload']["tmp_name"][$fileCount];
        //echo "<br>size:" . $_FILES['picUpload']["size"][$fileCount];
        $imageTotalCount = $imageCount+$fileCount; //new filenumber - already added 1 to offset the array count starting at 0
        $maxDim = 400;
        $file_name = $herbName . $imageTotalCount . ".jpg";
        //echo "<br/>NAME:" . $file_name;

        $target_path_upload = $target_path . $file_name;
        $target_path_thm_upload = $target_path_thm . $file_name;
			
        $datarray["files"][$fileCount] = $file_name;
			
        if(move_uploaded_file($_FILES['picUpload']['tmp_name'][$fileCount], $target_path_upload))
        {
            //now resize and copy the image to 400x400
            list($width, $height, $type, $attr) = getimagesize( $target_path_upload );
            if ( $width > $maxDim || $height > $maxDim )
            {
                $target_filename = $file_name;
                $ratio = $width/$height;
                if( $ratio > 1) 
                {
                    $new_width = $maxDim;
                    $new_height = $maxDim/$ratio;
                } else {
                    $new_width = $maxDim*$ratio;
                    $new_height = $maxDim;
                }
                $src = imagecreatefromstring( file_get_contents( $target_path_upload ) );
                $dst = imagecreatetruecolor( $new_width, $new_height );
                imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
                imagepng( $dst, $target_path_thm_upload ); // adjust format as needed
  						
                //imagedestroy( $src );
                //imagedestroy( $dst );
            }

			
            echo "The file ".  strtoupper(basename( $target_path_upload )). " has been uploaded!</br>";
        }
        else
        {
            $fileError = 1;
            echo "There was an error uploading the file, please try again!";
        }

    }

/*

    $maxDim = 800;
    $file_name = $_FILES['myFile']['tmp_name'];
    list($width, $height, $type, $attr) = getimagesize( $file_name );
    if ( $width > $maxDim || $height > $maxDim ) {
        $target_filename = $file_name;
        $ratio = $width/$height;
        if( $ratio > 1) {
            $new_width = $maxDim;
            $new_height = $maxDim/$ratio;
        } else {
            $new_width = $maxDim*$ratio;
            $new_height = $maxDim;
        }
        $src = imagecreatefromstring( file_get_contents( $file_name ) );
        $dst = imagecreatetruecolor( $new_width, $new_height );
        imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
        imagedestroy( $src );
        imagepng( $dst, $target_filename ); // adjust format as needed
        imagedestroy( $dst );
    }





    [picUpload] => Array
    (
        [name] => Array
        (
            [0] => arnica1.jpg
        )
        [type] => Array
        (
            [0] => image/jpeg
        )
        [tmp_name] => Array
        (
            [0] => /tmp/phpuydWGb
        )
        [error] => Array
        (
            [0] => 0
        )
        [size] => Array
        (
            [0] => 193499
        )
    )
*/

    //echo "<pre>";
    //print_r($datarray);
    //echo "</pre>";

    //the save function will return either successful or failed
    $result = $herbs->save_herb_images($datarray);

    $title = "<strong>Image(s) Upload: " . $result . "</strong><br>";
    return "done";
}//end saveHerb

