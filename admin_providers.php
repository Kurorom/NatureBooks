<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){

    header('location:login.php');
}



if(isset($_POST['update_provider'])){
    $update_provider_id = $_POST['update_provider_id'];
    $update_name = $_POST['update_name'];
    $update_founding_year = $_POST['update_founding_year'];
    
    $update_query = "UPDATE providers SET name = '$update_name' , founding_year='$update_founding_year' WHERE id='$update_provider_id'" ;
     mysqli_query($conn,$update_query) or die ('aaa');
      header('location:admin_providers.php');
 }

 if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn,"DELETE FROM providers where id = '$delete_id'") or die('query failed'); 
   header('location:admin_books.php');
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


<section class="edit-provider-form">
   <div class="edit-provider-div">
   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM providers WHERE id = '$update_id'") or die('test222');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_provider_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter name">
      <input type="text" name="update_founding_year" value="<?php echo $fetch_update['founding_year']; ?>" class="box" required placeholder="enter ffounding_year">
    <div  class="fit-btn">
      <input type="submit" value="update" name="update_provider" class="btn">
      </div>
    </form>
    <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-provider-form").style.display = "none";</script>';
      }
   ?>
</section>
<section class="show-authors">
   <h3 class="h_title">Providers Management</h3>
   <div class="box-container">

   <?php    
            $query = "SELECT * FROM Providers";
            $select_providers = mysqli_query($conn,$query) or die('query failed');
            if(mysqli_num_rows($select_providers)>0){
               while($fetch_providers= mysqli_fetch_assoc($select_providers)){

   ?>

   <table class="table table-bordered table-striped mt4" >
      <thread>
         <tr>
            <th>id</th>
            <th>founding_year</th>
            <th>buttons</th>
         </tr>
         <tr>
            <th><?php echo $fetch_providers['id']?></th>
            <th><?php echo $fetch_providers['name']?></th>
            <th><?php echo $fetch_providers['founding_year']?></th>
            
            <th>
               <a href="admin_providers.php?update=<?php echo $fetch_providers['id'];?>" class="option-btn">update</a> 
               <a href="admin_providers.php?delete=<?php echo $fetch_providers['id'];?>"
               class="delete-btn" onclick="return confirm('delete this provider?');">delete</a>
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