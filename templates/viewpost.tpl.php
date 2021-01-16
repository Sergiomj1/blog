<?php


if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<?php include 'header.tpl.php'; ?>

    <div class="row">
    <div class="col-lg-12 col-md-10 mx-auto">
        <div class="parentview">
            <div class="divview1"><img class="imgdetails" src="<?php  echo $post['imagen'];?>"/></div>
            <div class="divview2"><?php  echo $post['title'];?></div>
            <div class="divview3"><?php  echo $post['contenido'];?></div></div>
        </div>
    </div>
  </div>

<div class="emptyspace"></div>

<div class="row">
    <div class="col-lg-12 col-md-10 mx-auto">
        <h1 class="titles">Comentarios</h1>
   <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] == 1): ?>
        <form class="form" action="<?=BASE;?>/coment/add" method="POST" enctype="multipart/form-data">
            <label>Comentario</label><br />
            <input type="textarea" name="comment" required /><br />
            <input type="hidden" name="id" value="<?php  echo $post['id'];?>"/>
            <input class="btn btn-success" type="submit" value="Añadir comentario" />
        </form>
   <?php endif; ?>
     <div class="emptyspaces"></div>

      <?php if (isset($post['comentarios'])): ?>
  <?php foreach ($post['comentarios'] as $comentario): ?>
        <div class="parent">
            <div class="div1comment"><?php  echo $comentario['user']['username'];?></div>
            <div class="div2comment"><?php  echo $comentario['comment'];?></div>
        </div>

  <?php endforeach; ?>
      <?php endif; ?>


    </div>
    </div>






    <?php include 'footer.tpl.php';