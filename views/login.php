<?php App\Renderer::extend('/layout.php'); 

if (isset($errs)){
?>
<ul>
<?php
    foreach ($errs as $err){
?>
    <li> <?=$err ?> </li>
<?php
    }
}
?>

</ul>

<form  method="POST">
    <input type="text" name="login" placeholder="login">
    <input type="password" name="password" placeholder="password">
    <input type="submit" value="login">
</form>

