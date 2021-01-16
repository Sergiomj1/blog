<?php
    $posts = $dataview['posts'];

    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
?>
<?php include 'header.tpl.php'; ?>

<!-- Page Header -->
<header class="masthead"">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-10 mx-auto">
                <div class="site-heading">
                    <h1>Blog 3djuegos</h1>
                    <span class="subheading">El mejor blog de M7</span>
                </div>
            </div>
        </div>
    </div>
</header>
    <div class="container">
        <div class="row"></div>
        <div class="col-lg-12 col-md-10 mx-auto">
            <div class="site-heading">
                <h1 class="titles">Myposts</h1>
                <div class="row">
                    <?php foreach ($posts as $post): ?>
                        <div class="parent">
                            <div class="div1"><img class="imgposting" src="<?php  echo $post['imagen'];?>"/></div>
                            <div class="div2"><a href="<?php echo BASE; ?>/post/details/id/<?php echo $post['id']; ?>"><?php  echo $post['title'];?></a></div>


                    </div>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] == 2): ?>
                            <a class="btn btn-default " href="<?php echo BASE; ?>/post/edit/id/<?php echo $post['id']; ?>">Editar</a>
                            <a class="btn btn-danger " href="<?php echo BASE; ?>/post/delete/id/<?php echo $post['id']; ?>">Eliminar</a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>



<?php include 'footer.tpl.php';
