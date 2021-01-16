<?php $post = $dataview['post']; ?>
<?php include 'header.tpl.php'; ?>



<form class="form" action="<?=BASE;?>/post/edit" method="POST" enctype="multipart/form-data">
    <label>Titulo</label><br />
    <input type="text" name="titulo" required value="<?php echo $post['title']; ?>" /><br />
    <label>Contenido</label><br />
    <textarea name="contenido" placeholder="Contenido del post..." required><?php echo $post['contenido']; ?></textarea><br />
    <label>Imagen</label><br />
    <input class="center" type="file" name="imagen" /><br />
    <label>Categoria</label><br />
    <input type="text" name="categorias" required value="<?php echo $post['category']['name']; ?>" /><br />
    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
    <input class="btn btn-success" type="submit" value="Editar post" />
</form>


<?php include 'footer.tpl.php'; ?>
