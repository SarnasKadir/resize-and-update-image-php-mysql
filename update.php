<?php
 
include "conn.php"; 
 
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
			$image_description = trim($_POST["image_description"]);
			$image_category = trim($_POST["image_category"]);
            $image_name = trim($_POST["image_name"]);	
	
	
	if (!empty($_FILES['imageToUpload']['name']))  {

        $file_type = $_FILES['imageToUpload']['type'];
        $allowed = array(
            "image/jpg",
            "image/png",
            "image/jpeg",
            "image/gif"
        );

        if (in_array($file_type, $allowed)){
			

            $file_ext = pathinfo($_FILES['imageToUpload']['name'], PATHINFO_EXTENSION);
            
			
			$image_file_name = preg_replace('/[\s$@_*]+/', '_', $image_name. '.' . $file_ext);

			if (!file_exists("thumbs/" . $image_file_name) && !file_exists("original/" . $image_file_name)){

                $upload_image = '' . basename($image_file_name);
				
				
				
// -------------------------------------------------------------
// insert into mysql database



$sql = "INSERT INTO images (image_name, image_file_name, image_category, image_description ) VALUES (:image_name, :image_file_name, :image_category, :image_description)";	

if($stmt = $pdo->prepare($sql)){
	
	$stmt->bindParam(":image_name", $param_image_name);	
	$stmt->bindParam(":image_file_name", $param_image_file_name);
	$stmt->bindParam(":image_category", $param_image_category);
	$stmt->bindParam(":image_description", $param_image_description);
	
	
	
	
	$param_image_name = $image_name;
    $param_image_file_name  = $image_file_name;
	$param_image_category  = $image_category;
	$param_image_description  = $image_description;
	
	if($stmt->execute()){			 			 
            $last_id = $pdo->lastInsertId();
            header("location: single_img_detail.php?id=".$last_id);
  
		} else{
			echo "Error!!";
	}
}

unset($stmt); 
 

// insert into mysql database ends
// ------------------------------------------------------

                if (move_uploaded_file($_FILES['imageToUpload']['tmp_name'], $upload_image))                {
                    $thumbnail = 'thumbs/' . $image_file_name;
                    $original = 'original/' . $image_file_name;
                    list($width, $height) = getimagesize($upload_image);
                    
					$thumb_width = '100';
					$thumb_height = '100';            
					$original_width = '600';
			
                    // this function get percent heighs of original image and applies to the rezised one to preserve image ratio .
                    $percent = $height / $width;
                    $percent = number_format($percent * 100);
                    $original_height = ($percent / 100) * $original_width;

                    $thumb_create = imagecreatetruecolor($thumb_width, $thumb_height);
                    $original_create = imagecreatetruecolor($original_width, $original_height);

							switch ($file_ext){
								case 'jpg':
									$source = imagecreatefromjpeg($upload_image);
								break;
								case 'jpeg':
									$source = imagecreatefromjpeg($upload_image);
								break;

								case 'png':
									$source = imagecreatefrompng($upload_image);
								break;
								case 'gif':
									$source = imagecreatefromgif($upload_image);
								break;
								default:
									$source = imagecreatefromjpeg($upload_image);
							}

							imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
							imagecopyresized($original_create, $source, 0, 0, 0, 0, $original_width, $original_height, $width, $height);

							switch ($file_ext) {
								case 'jpg' || 'jpeg':
									imagejpeg($thumb_create, $thumbnail);
									imagejpeg($original_create, $original);
								break;
								case 'png':
									imagepng($thumb_create, $thumbnail);
									imagepng($original_create, $original);
								break;

								case 'gif':
									imagegif($thumb_create, $thumbnail);
									imagegif($original_create, $original);
								break;
								default:
									imagejpeg($thumb_create, $thumbnail);
									imagejpeg($original_create, $original);
							}

                }
                else {
                    return false;
                }
                
                unlink($upload_image);
               
                //echo "<script>if ( window.history.replaceState ) {window.history.replaceState( null, null, window.location.href );}</script>";                 
                //echo "<script>location.reload(); return false;</script>";

            }
            else { 
                $formSumidInfo =  ' The image name exist! ';
            }

        }
        else{ 
            $formSumidInfo =  ' Only jpg, png, gif, files are allowed. ';
        }

    }
    else{  
      
	  $formSumidInfo =  'Select an image please! ';

    } 
    
}else{

// ----------------------------------------------------------------------------------------------
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
 

    $sql = "SELECT * FROM images WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                 
                
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    unset($stmt);
    
    // Close connection
    unset($pdo);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}

}
?>
 

 
 


 
<?php
 
$formSumidInfo = "";
$last_id = "";

?>



<!DOCTYPE html>
<html>
<head>
<title>Upload image, Resize</title>
<link rel="stylesheet" href="style.css">
</head>
<body> 
<ul>
<li><a href="upload.php">Upload image</a><br></li>
<li><a href="thumbnail.php">Thumb nails view</a></li>
</ul>
<hr/> 
<h1>Upload image</h1>

<form id="myform" method="post" enctype="multipart/form-data" >

<p style="color:red;"><?php echo $formSumidInfo;?></p>
 
<p>Select an image: <br> <input type="file" name="imageToUpload" onchange="previewFile()"><br>
<img src="thumbs/<?php echo $row["image_file_name"]; ?>" style="margin:10px;display:inline-block; width:100px; height:100px;"></p>

<p>Image name: <br><input type="text" name="image_name" value="<?php echo $row["image_name"];?>" required></p>

<p>Image category:<br> 
	<select name="image_category" required>
		<option value="<?php echo $row["image_category"];;?>" selected><?php echo $row["image_category"];?></option>
		<option value="Pizza">Pizza </option>
		<option value="Brew">Brew (hot drinks)</option>
		<option value="Icecream">Icecream</option>
		<option value="Desserts">Desserts</option>
		<option value="Beverage">Beverage (cold drinks)</option>
	</select>
<p>

<p>Image description: <br><textarea type="text" name="image_description"> <?php echo $row["image_description"];?> </textarea></p>
 
<p><input type="submit" name="submit" value="Upload the image"></p>
</form>
<script>
function previewFile() {
  var preview = document.querySelector('img');
  var file    = document.querySelector('input[type=file]').files[0];
  var reader  = new FileReader();

  reader.onloadend = function () {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
  }
}
</script>

 
 
</body>
</html>