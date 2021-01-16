<?php include 'header.tpl.php'; ?>



    <form class="form" action="<?=BASE;?>/coment/add" method="POST" enctype="multipart/form-data">
        <label>Ti</label><br />
        <input type="textarea" name="comment" required /><br />
        <input class="btn btn-success" type="submit" value="Crear post" />
    </form>


<?php include 'footer.tpl.php'; ?>