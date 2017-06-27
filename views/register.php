 <?php if (isset($errors)) {
   ?><p><?php echo $errors?></p>
<?php } ?>

<form method="POST">
    <input type="text" name="login" placeholder="login" required>
    <input type="password" name="password" placeholder="password" required>
    <input type="password" name="password_confirmation" placeholder="verify password" required>
    <button type="submit">register</button>
</form>
