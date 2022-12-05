
<?php
 
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    include "conn.php";

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
                //header("location: error.php");
				echo "1";
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
    //header("location: error.php");
	echo "2";
    exit();
}
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
<h1>Single image preview and details</h1>

<p><b>Image Name</b><br><?php echo $row["image_name"]; ?></p>
<p><b>Name catergory</b><br><?php echo $row["image_category"]; ?></p>
<p><b>Image file name</b><br><?php echo $row["image_file_name"]; ?></p>
<p><b>Image description</b><br><?php echo $row["image_description"]; ?></p>
<p><a href="delete.php?id=<?php echo $row["id"]; ?>" title="Delete image">Delete </a> 
<a href="update.php?id=<?php echo $row["id"]; ?>" title="update image">Update </a></p>
<p><img src="original/<?php echo $row["image_file_name"]; ?>" alt="<?php echo $row["image_name"]; ?>" /></p>
 



</body>
</html>