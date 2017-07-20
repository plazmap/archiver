<!DOCTYPE html>
<html>

<head>
    <title> Plazma's toy </title>
    <style>

h1 {
    color: #000080;
    font-size: 48px;
    text-decoration: underline;
}

h2 {
    font-size: 24px;
}

div.menu{
    position: fixed;
    top: 0;
    width: 100%;
    background-color: #000033;
    
}

div.menu a{
    color: #b3b3ff;
    float: left;
    padding: 10px;
    text-decoration: none;
}

div.menu button{
    background-color: Transparent;
    border: none;
    cursor:pointer;    
    color: #b3b3ff;
    float: right
    padding: 10px;
    text-decoration: none;
}


div.main{
    margin-top: 50px;
    margin-left: 100px;
}

    </style>

</head>

<body>

    <div class="menu">
    
        <a href="/home">Home </a>
        <a href="/stupid">Something stupid</a>

<?php if (!isset($_SESSION['user'])){ ?>
        <a href="/register">Register </a>
        <a href="/login">Login </a>
<?php }else{ ?>
        <a href="/account">My account </a>
        <a href="/disconnect">Log out </a>
<?php } ?>
 

    </div>

    <div class="main">
<?= $output ?> 
    </div>

</body>

</html>
