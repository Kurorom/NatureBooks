let userBox = document.querySelector('.header .header-2 .user-box');

document.querySelector('#user-btn').onclick = () =>{
   userBox.classList.toggle('active');
   navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .header-2 .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

window.onscroll = () =>{
   userBox.classList.remove('active');
   navbar.classList.remove('active');

   if(window.scrollY > 60){
      document.querySelector('.header .header-2').classList.add('active');
   }else{
      document.querySelector('.header .header-2').classList.remove('active');
   }
}


$(document).ready(function(){

   $("#live_search").keyup(function(){

      var input = $(this).val();
      
      if(input !=""){
         $.ajax({
            url:"search_result.php",
            method:"POST",
            data:{input:input},

            success:function(data){
               $("#search_result").html(data);
            }
         });
      }else{
         // $("#search_result").css("display" ,"none");
      }
   }); 
   
});