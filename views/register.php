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

div.main{
    margin-top: 50px;
    margin-left: 100px;
}

    </style>

</head>

<body>

    <div class="menu">
        <a href="/home">Home </a>
        <a href="/login">Login </a>
        <a href="/register">Register </a>
        <a href="/stupid">Something stupid</a>

    </div>

    <div class="main">
<?php 
if (isset($errs)) {
?>
<ul>
<?php 
    foreach ($errs as $err){
?>
        <li> <?= $err ?> </li>
<?php 
    }
?>
</ul>
<?php 
}
?>
        <form method="POST">
            <input type="text" name="login" placeholder="login" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="password" name="password_confirmation" placeholder="verify password" required>
            <button type="submit">register</button>
        </form>
    
    </div>

</body>

</html>
