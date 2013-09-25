<?php require_once('header.php'); ?>

<div class="content home clearfix">

<?php $rowContents = $this->get('model_content') ?>
<?php include($this->pathView('_contents')); ?>

</div>

<?php require_once('footer.php'); ?>
