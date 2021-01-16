<?php include 'header.tpl.php'; ?>



<form class="form" action="<?=BASE;?>/post/add" method="POST" enctype="multipart/form-data">
    <label>Titulo</label><br />
    <input type="text" name="titulo" required /><br />
    <label>Contenido</label><br />
    <textarea name="contenido" placeholder="Contenido del post..." required></textarea><br />
    <label>Imagen</label><br />
    <input class="center" type="file" name="imagen" /><br />
    <label>Categoria</label><br />
    <input type="text" name="categorias" required /><br />
    <input class="btn btn-success" type="submit" value="Crear post" />
</form>


<?php include 'footer.tpl.php'; ?>
