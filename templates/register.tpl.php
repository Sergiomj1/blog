<?php

include 'header.tpl.php';
?>

<div class="row">
<div class="container">
    <div class="col-lg-12 col-md-10 mx-auto">
            <form class="form" action="<?=BASE;?>/user/register" method="POST">
                <label>Nombre de usuario</label><br />
                <input type="text" name="username" required /><br />
                <label>Correo</label><br />
                <input type="text" name="email" required /><br />
                <label>Contraseña</label><br />
                <input type="password" name="password" required /><br />
                <input class="btn btn-default" type="submit" value="Registrarse" />
            </form>
    </div>
</div>
</div>
<?php include 'footer.tpl.php'; ?>
