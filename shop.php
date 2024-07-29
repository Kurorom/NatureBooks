<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $book_title= $_POST['book_title'];
   $book_genre= $_POST['book_genre'];
   $book_price= $_POST['book_price'];
   $book_image= $_POST['book_image'];
   $product_quantity= $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * from cart WHERE title='$book_title' AND user_id='$user_id'") 
   or die('query failed');

   if(mysqli_num_rows($check_cart_numbers)>0){
       $message[]= 'already added to cart';
   }else{
        mysqli_query($conn, "INSERT INTO cart (user_id,title,genre,price,quantity,image) VALUES('$user_id',
        '$book_title','$book_genre','$book_price','$product_quantity','$book_image')") or die('query failed');
        $message[]='product added to cart';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>our shop</h3>
   <p> <a href="home.php">home</a> / shop </p>
</div>

<section class="products">

<h1 class="h_title">latest products</h1>

<div class="box-container">

<?php
    
    $select_books = mysqli_query($conn, "SELECT books.title,books.genre,books.publication_year,books.price,books.image,authors.first_name,authors.last_name FROM books
     INNER JOIN authors ON books.author_id = authors.id ")  or die('query failed');

    if(mysqli_num_rows($select_books)>0){
        while($fetch_books = mysqli_fetch_assoc($select_books) ){
            
?>
<form action="" method="post" class="box">
<img src="uploaded_img/<?php echo $fetch_books['image'];?>" alt="">
<div class="title"><?php echo $fetch_books['title'] ?></div>
<div class="author"><?php echo'by '. $fetch_books['first_name'].$fetch_books['last_name'] ?></div>
<div class="genre">-> <?php echo $fetch_books['genre']?></div>
<div class="price">$<?php echo $fetch_books['price'] ?>/-</div>
<input type="number" min="1" name="product_quantity" value="1" class="qty">
<input type="hidden" name="book_title" value="<?php echo $fetch_books['title'] ?>">
<input type="hidden" name="book_genre" value="<?php echo $fetch_books['genre'] ?>">
<input type="hidden" name="book_price" value="<?php echo $fetch_books['price'] ?>">
<input type="hidden" name="book_image" value="<?php echo $fetch_books['image'] ?>"> 
<input type="submit" value="add to cart" name="add_to_cart" class="btn">
</form>

<?php
        }

    }else{
        echo'<p class="empty> no products added yet!</p>"';
    }

?>

</div>

</section>







<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>