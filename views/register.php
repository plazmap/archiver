<?php if (isset($err)) { ?>
<p><?= $err ?></p>
<?php } ?>
<form method="POST">
	<input type="text" name="login" placeholder="login" required>
	<input type="password" name="password" placeholder="password" required>
    <input type="password" name="pass_verif" placeholder="verify password" required>
    <button type="submit">register</button>
</form>
