<?php 

 include("config.php");

    if(isset($_POST['input'])){
       $input=$_POST['input'];
       $query="SELECT books.title,books.genre,books.publication_year,books.price,books.image,authors.first_name,authors.last_name 
       FROM books INNER JOIN authors ON books.author_id = authors.id INNER JOIN providers ON books.providers_id=providers.id  
       WHERE books.title LIKE '{$input}%' OR books.genre LIKE '{$input}%' OR books.publication_year LIKE '{$input}%'
       OR authors.first_name LIKE '{$input}%' OR providers.name LIKE '{$input}%' ";
       $select_books = mysqli_query($conn,$query) or die('query failed');
       if(mysqli_num_rows($select_books) > 0){
       while($fetch_books = mysqli_fetch_assoc($select_books)){
 ?>
   
    <form action="" method="post" class="box">
        <img src="uploaded_img/<?php echo $fetch_books['image'];?>" alt="">
        <div class="title"><?php echo $fetch_books['title'] ?></div>
        <div class="author"><?php echo'by '. $fetch_books['first_name'].$fetch_books['last_name'] ?></div>
        <div class="genre">-> <?php echo $fetch_books['genre']?></div>
        <div class="price">$<?php echo $fetch_books['price'] ?>/-</div>
        <input type="hidden" name="book_title" value="<?php echo $fetch_books['title'] ?>">
        <input type="hidden" name="book_genre" value="<?php echo $fetch_books['genre'] ?>">
        <input type="hidden" name="book_price" value="<?php echo $fetch_books['price'] ?>">
        <input type="hidden" name="book_image" value="<?php echo $fetch_books['image'] ?>"> 
        <input type="submit" value="add to cart" name="add_to_cart" class="btn">

     </form>
 <?php
          }
       }else{
        echo '<p class="empty">no result found!</p>';
       }
    }else{
       echo '<p class="empty">search something!</p>';
    }
 ?>
