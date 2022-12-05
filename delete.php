<?php
 
if(isset($_POST["id"]) && !empty($_POST["id"])){
     
    require_once "conn.php";
    
    
    $sql = "DELETE FROM images WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
         
        $stmt->bindParam(":id", $param_id);
        
         
        $param_id = trim($_POST["id"]);
        
         
        if($stmt->execute()){
             
			$image_file_name = trim($_POST["image_file_name"]);
			unlink("thumbs/".$image_file_name);
			unlink("original/".$image_file_name);
            header("location: thumbnail.php");
		 
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
 
    unset($stmt);
    
 
    unset($pdo);
} else{
 
    if(empty(trim($_GET["id"]))){
 
        header("location: thumbnail.php");
 
        exit();
    }
}
?>

<?php
include "conn.php";
$id = trim($_GET["id"]);
$stmt = $pdo->prepare("SELECT * FROM images WHERE id=? LIMIT 1"); 
$stmt->execute([$id]); 
$row = $stmt->fetch();


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
<h1>Delete Image?</h1>
<p><img src="thumbs/<?php echo $row["image_file_name"];?>"></p>
 

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
 
<input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
<input type="hidden" name="image_file_name" value="<?php echo $row["image_file_name"];?>"/>

<p>Are you sure you want to delete this image?</p> 
<p><input type="submit" value="Yes" ><br> the image will be deletes from the database and from the folders</p>
</form>
<p><a href="single_img_detail.php?id=<?php echo trim($_GET["id"]); ?>">No!<br>back to the preview?</a></p>

</body>
</html>