let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');
let accountBox =  document.querySelector('.header .account-box')

document.querySelector('#menu-btn').onclick= () =>{
    navbar.classList.toggle('active');
    accountBox.classList.remove('active');
}

document.querySelector('#user-btn').onclick= () =>{
    accountBox.classList.toggle('active');
    navbar.classList.remove('active');
}

window.onscroll = () => {
    navbar.classList.remove('active');
    accountBox.classList.remove('active');
}

document.querySelector('#close-update').onclick=() =>{
    document.querySelector('.edit-book-form').style.display = 'none';
    window.location.href = 'admin_books.php';
}

document.querySelector('#close-update-author').onclick=() =>{
    document.querySelector('.edit-author-form').style.display = 'none';
    window.location.href = 'admin_authors.php';
}

document.querySelector('#close-update-provider').onclick=() =>{
    document.querySelector('.edit-provider-form').style.display = 'none';
    window.location.href = 'admin_providers.php';
}