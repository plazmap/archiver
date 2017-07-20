<?php App\Renderer::extend('/layout.php');
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

