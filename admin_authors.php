<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){

    header('location:login.php');
}



if(isset($_POST['update_author'])){
    $update_author_id = $_POST['update_author_id'];
    $update_first_name = $_POST['update_first_name'];
    $update_last_name = $_POST['update_last_name'];
    
    $update_query = "UPDATE authors SET first_name = '$update_first_name' , last_name='$update_last_name' WHERE id='$update_author_id'" ;
     mysqli_query($conn,$update_query) or die ('aaa');
      header('location:admin_authors.php');
 }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>books</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

    
</head>
<body>
    
<?php include 'admin_header.php';  ?>


<section class="edit-author-form">
   <div class="edit-author-div">
   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM authors WHERE id = '$update_id'") or die('test222');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_author_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="text" name="update_first_name" value="<?php echo $fetch_update['first_name']; ?>" class="box" required placeholder="enter first_name">
      <input type="text" name="update_last_name" value="<?php echo $fetch_update['last_name']; ?>" class="box" required placeholder="enter flast_name">
    
      <input type="submit" value="update" name="update_author" class="btn">
    </form>
    <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-author-form").style.display = "none";</script>';
      }
   ?>
</section>
<section class="show-authors">
   <h3 class="h_title">Authors Management</h3>
   <div class="box-container">

   <?php    
            $query = "SELECT * FROM authors";
            $select_authors = mysqli_query($conn,$query) or die('query failed');
            if(mysqli_num_rows($select_authors)>0){
               while($fetch_authors= mysqli_fetch_assoc($select_authors)){

   ?>

   <table class="table table-bordered table-striped mt4" >
      <thread>
         <tr>
            <th>id</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>buttons</th>
         </tr>
         <tr>
            <th><?php echo $fetch_authors['id']?></th>
            <th><?php echo $fetch_authors['first_name']?></th>
            <th><?php echo $fetch_authors['last_name']?></th>
            
            <th>
               <a href="admin_authors.php?update=<?php echo $fetch_authors['id'];?>" class="option-btn">update</a> 
               <a href="admin_authors.php?delete=<?php echo $fetch_authors['id'];?>"
               class="delete-btn" onclick="return confirm('delete this author?');">delete</a>
               </div>
            
            </th>
            
         </tr>
      </thread>
   </table>
   <?php
               }
            
            }
      ?>
   </div>
  </section>

<script src="js/admin_script.js"></script>
</body> 
</html>