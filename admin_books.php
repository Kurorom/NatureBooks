<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){

    header('location:login.php');
}

if(isset($_POST['add_author']))  {

   $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
   $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);

   $select_author_first_name = mysqli_query($conn, "SELECT first_name FROM authors  WHERE first_name='$first_name' ") or die('query failed');

   if(mysqli_num_rows($select_author_first_name)>0){
      $message[] = 'author already added';
  } else {
   $add_author_query = mysqli_query($conn, "INSERT INTO `authors`(first_name,last_name) VALUES('$first_name', '$last_name')") or die('query failed');
   
   $message[] = 'author added successfully!';

  }
   
}

if(isset($_POST['add_provider']))  {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $founding_year = $_POST['founding_year'];

   $select_provider_name = mysqli_query($conn, "SELECT name FROM providers  WHERE name='$name' ") or die('query failed');

   if(mysqli_num_rows($select_provider_name)>0){
      $message[] = 'provider already added';
  } else {
   $add_provider_query = mysqli_query($conn, "INSERT INTO `providers`(name,founding_year) VALUES('$name', '$founding_year')") or die('query failed');
   
   $message[] = 'provider added successfully!';

  }
   
}


if(isset($_POST['add_book'])){

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $publication_year = $_POST['publication_year'];
    $price = $_POST['price'];
    $author_id = $_POST['author_id'];
    $provider_id=$_POST['provider_id'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;
 
    $select_book_title = mysqli_query($conn, "SELECT title FROM `books` WHERE title = '$title'") or die('query failed');
 
    if(mysqli_num_rows($select_book_title) > 0){
       $message[] = 'book already added';
    }else{
       $add_book_query = mysqli_query($conn, "INSERT INTO `books`(title,genre,publication_year, price, image,author_id,providers_id)
        VALUES('$title','$genre','$publication_year', '$price', '$image','$author_id','$provider_id')");
        
       if($add_book_query){
          if($image_size > 20000000){
              $message[] = 'image size is too large';
           }else{
             move_uploaded_file($image_tmp_name, $image_folder);
             $message[] = 'book added successfully!';
           }
        }else{
          $message[] = 'book could not be added!';
       }
    }
 }


 if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_image_query= mysqli_query($conn,"SELECT image FROM books WHERE id='$delete_id'") or die ('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    $fetch_delete_image['image'];
    unlink('uploaded_img/' .$fetch_delete_image['image']);
    mysqli_query($conn,"DELETE FROM books where id = '$delete_id'") or die('query failed'); 
    header('location:admin_books.php');
 }

 if(isset($_POST['update_book'])){
    $update_p_id = $_POST['update_p_id'];
    $update_title = $_POST['update_title'];
    $update_genre = $_POST['update_genre'];
    $update_price = $_POST['update_price'];
    $update_publication_year = $_POST['update_publication_year'];
    $update_author_id = $_POST['update_author_id'];
    $update_provider_id=$_POST['update_provider_id'];
    mysqli_query($conn, "UPDATE `books` SET title = '$update_title',genre='$update_genre',
    publication_year='$update_publication_year', price = '$update_price',author_id='$update_author_id',
    providers_id='$update_provider_id' WHERE id = '$update_p_id'") or die('test1');
    //error test
  
   
   
    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/'.$update_image;
    $update_old_image = $_POST['update_old_image'];

    
      if($update_image_size > 2000000){
             $message[]= 'image file size is too large';
       }else {
            if(!empty($update_image)){
            mysqli_query($conn, "UPDATE books SET image='$update_image' WHERE id = '$update_p_id'") 
            or die('test');
            
            move_uploaded_file($update_image_tmp_name,$update_folder);
            unlink('uploaded_img/'.$update_old_image);
            $message[] = '1';
            }
         }
    
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

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

    
</head>
<body>
    
<?php include 'admin_header.php';  ?>

<!-- books CRUD -->



<section class="add-books">
  


    <h1 class="h_title">shop books </h1>

    <div class="box-container">

    <!-- add author -->
      
    <form action="" class="box" method="post" id="smaller" enctype="multipart/form-data">

      <h3>add author</h3>
      <input type="text" name="first_name" class="box" placeholder="enter author's first name" required>
      <input type="text" name="last_name" class="box" placeholder="enter author's first name" required>
      
      <input type="submit" value="add author" name="add_author" class="btn">
   </form> 

  
   
   <!-- add book -->
   <form action="" class="box" method="post" id="add_book" enctype="multipart/form-data">
      
      <h3>add book</h3>
      <input type="text" name="title" class="box" placeholder="enter book title" required>
      <input type="text" name="genre" class="box" placeholder="enter book genre" required>
      <input type="number" min="1000" max="2099" name="publication_year" class="box" placeholder="enter publication year" required>
      <input type="number" min="0" name="price" class="box" placeholder="enter book price" required>
      

      <?php
      $query= "SELECT * FROM authors";
      $result= mysqli_query($conn, $query);
      if (mysqli_num_rows($result) > 0) {
         $authors = mysqli_fetch_all($result, MYSQLI_ASSOC);
     } else {
         $authors = [];

     }

      echo '<select name="author_id" class="box">';
      echo '<option value="" disabled selected>Choose author</option>';
      foreach ($authors as $author) {
         echo '<option value="' . $author['id'] . '">' . $author['first_name'] ." " .$author['last_name'].    '</option>';
      }
      echo '</select>';
      ?>

<?php
      $query= "SELECT * FROM providers";
      $result= mysqli_query($conn, $query);
      if (mysqli_num_rows($result) > 0) {
         $providers = mysqli_fetch_all($result, MYSQLI_ASSOC);
     } else {
         $providers = [];

     }

      echo '<select name="provider_id" class="box">';
      echo '<option value="" disabled selected>Choose provider</option>';
      foreach ($providers as $provider) {
         echo '<option value="' . $provider['id'] . '">' . $provider['name'] .    '</option>';
      }
      echo '</select>';
      ?>
      

      <input type="file" name="image"      class="box" required>
      <input type="submit" value="add book" name="add_book" class="btn">
      
   </form>
  
    <!-- add provider -->
    <form class="box" method="post" id="smaller" enctype="multipart/form-data">

   <h3>add provider</h3>
   <input type="text" name="name" class="box" placeholder="enter provider name" required>
   <input type="number" min="1000" max="2099" name="founding_year" class="box" placeholder="enter founding year" required>
   <input type="submit" value="add provider" name="add_provider" class="btn">
   </form>

      </div>

   
</section>

<!-- show books -->

<section class="show-books">

    <div class="box-container">
        <?php 
            $select_books = mysqli_query($conn,"SELECT books.id,books.title,books.genre,books.publication_year,books.price,books.image,authors.first_name,authors.last_name FROM books
            INNER JOIN authors ON books.author_id = authors.id") or die('query failed');
            if(mysqli_num_rows($select_books)>0){
                    while($fetch_books= mysqli_fetch_assoc($select_books)){

                    
        ?>
        <div class="box">
            <img src="uploaded_img/<?php echo $fetch_books['image'];?>"
            alt="">
            <div class="title"><?php echo $fetch_books['title'];?></div>
           
            <div class="author"><?php echo'by '. $fetch_books['first_name']." ".$fetch_books['last_name'] ?></div>
            <div class="genre"><?php echo $fetch_books['genre'];?></div>
            <div class="price">$<?php echo $fetch_books['price'];?>/-<</div>
            
               <div class="move-btn">
               <a href="admin_books.php?update=<?php echo $fetch_books['id'];?>"
               class="option-btn">update</a>
               <a href="admin_books.php?delete=<?php echo $fetch_books['id'];?>"
               class="delete-btn" onclick="return confirm('delete this book?');">delete</a>
               </div>
            
        </div>
        <?php
        }
            }else {
                echo '<p class="empty"> no book added yet!</p>';
            }
        ?> 
    </div>


</section>

<section class="edit-book-form">
   <div class="edit-book-div">
   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `books` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_title" value="<?php echo $fetch_update['title']; ?>" class="box" required placeholder="enter book title">
      <input type="text" name="update_genre" value="<?php echo $fetch_update['genre']; ?>" class="box" required placeholder="enter book genre">
      <input type="number" name="update_publication_year" value="<?php echo $fetch_update['publication_year']; ?>" min="1000" max="2099" class="box" required placeholder="enter book publication year">

      <?php
      $query= "SELECT * FROM authors";
      $result= mysqli_query($conn, $query);

      // $query2="SELECT authors.* FROM authors INNER JOIN books on authors.id=books.authors_id";
      if (mysqli_num_rows($result) > 0) {
         $authors = mysqli_fetch_all($result, MYSQLI_ASSOC);
     } else {
         $authors = [];

     }

      echo '<select name="update_author_id" class="box">';
       echo '<option value="" disabled >Choose author</option>';
      foreach ($authors as $author) {
         if($author['id']==$fetch_update['author_id']){
            echo '<option value="' . $author['id'] . '" selected>' . $author['first_name'] ." " .$author['last_name'].    '</option>';

         }else{
         echo '<option value="' . $author['id'] . '">' . $author['first_name'] ." " .$author['last_name'].    '</option>';
         }
      }
      echo '</select>';
      ?>

<?php
      $query= "SELECT * FROM providers";
      $result= mysqli_query($conn, $query);
      if (mysqli_num_rows($result) > 0) {
         $providers = mysqli_fetch_all($result, MYSQLI_ASSOC);
     } else {
         $providers = [];

     }

      echo '<select name="update_provider_id" class="box">';
      echo '<option value="" disabled >Choose provider</option>';
      foreach ($providers as $provider) {
         if($provider['id']==$fetch_update['providers_id']){
         echo '<option value="' . $provider['id'] . '" selected>' . $provider['name'] .    '</option>';
         }else {
         echo '<option value="' . $provider['id'] . '" >' . $provider['name'] .    '</option>';

         }
      }
      echo '</select>';
      ?>
      

      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter book price">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_book" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-book-form").style.display = "none";</script>';
      }
   ?>
   </div>
</section>


<script src="js/admin_script.js"></script>
</body> 
</html>