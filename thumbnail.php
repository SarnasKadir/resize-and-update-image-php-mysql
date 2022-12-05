<?php
$sql = "select * from images";
$selectdCategory = "all ";
if(isset($_GET["categories"]) && !empty(trim($_GET["categories"]))){
	switch ($_GET["categories"]) {
     case 'Pizza':
          $sql = "SELECT * FROM images WHERE image_category='Pizza'";
		  $selectdCategory = "Pizza ";
           break;
     case 'Brew';
         $sql = "SELECT * FROM images WHERE image_category='Brew'";
		 $selectdCategory = "Brew ";
          break;
     case 'Desserts';
         $sql = "SELECT * FROM images WHERE image_category='Desserts'";
		 $selectdCategory = "Desserts ";
          break;
     case 'Beverage';
         $sql = "SELECT * FROM images WHERE image_category='Beverage'";
		 $selectdCategory = "Beverage ";
          break;
     case 'all_categories';
         $sql = "SELECT * FROM images";
		 $selectdCategory = "all ";
          break;
     default:
          echo "<p><h4>No category found!</h4></p>";
     break;
}	
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
<h1>Thumbnails</h1> 
<p>
 Select a category<br>
<ul>
<li><a href="thumbnail.php?categories=Pizza">Pizza</a></li>
<li><a href="thumbnail.php?categories=Brew">Brew</a></li>
<li><a href="thumbnail.php?categories=Desserts">Desserts</a></li>
<li><a href="thumbnail.php?categories=Beverage">Beverage</a></li>
<li><a href="thumbnail.php?categories=all_categories">all_categories</a></li>
</ul>
</p>

<hr />
<h4>Selected category: <?php echo $selectdCategory;?> </h4>
 
<?php
 
require_once "conn.php";

 
 
if($result = $pdo->query($sql)){
if($result->rowCount() > 0){
 
while($image = $result->fetch()){
  echo "<a href='single_img_detail.php?id=". $image['id']."'  target='_blank' rel='noopener noreferrer' title='".$image["image_name"] ."'><img src='thumbs/".$image["image_file_name"] ."' alt='".$image["image_name"] ."'></a>"; 
}
 
 
unset($result);
} else{
	echo '<p>No image found</p>';
}
} else{
echo "<p> omething went wrong </p>";
}

 
unset($pdo);
?>

</body>
</html>