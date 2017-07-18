<?php
if (isset($errs)){
?>
<ul>
<?php
    foreach ($errs as $err){
?>
    <li>Connection with server lost. Please try again later.(<?=.$err ?>) </li>
<?php
    }
?>
</ul>
<?php
}
?>

<form  method="POST">
    <input type="text" name="login" placeholder="login">
    <input type="password" name="password" placeholder="password">
    <input type="submit" value="login">
</form>

