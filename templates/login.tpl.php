
<?php

include 'header.tpl.php';
?>

<div class="row">
    <div class="container">
        <div class="col-lg-12 col-md-10 mx-auto">
<form class="form center" action="<?=BASE;?>/user/login" method="post">
    <label>Nombre</label><br />
    <?php if(isset($_COOKIE) && !empty($_COOKIE['nombreUsuario'])): ?>
        <input type="text" name="email" value="<?php echo $_COOKIE['email']; ?>" required /><br />
    <?php else: ?>
        <input type="text" name="email" required /><br />
    <?php endif; ?>
    <label>Contraseña</label><br />
    <?php if(isset($_COOKIE) && !empty($_COOKIE['password'])): ?>
        <input type="password" name="password" value="<?php echo $_COOKIE['password']; ?>" required /><br />
    <?php else: ?>
        <input type="password" name="password" required /><br />
    <?php endif; ?>
    <label>Recordarme</label>
    <input style="width: 20px; height: 20px;" type="checkbox" name="recordar" value="1" /><br />
    <input class="btn btn-default" type="submit" value="entrar" />
</form>

        </div>
    </div>
</div>
<?php include 'footer.tpl.php'; ?>