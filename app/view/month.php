<?php require_once('header.php'); ?>

<div class="content month clearfix">
	<h1>All posts from <?php echo $this->get('month_year') ?></h1>

<?php $rowContents = $this->get('model_content') ?>
<?php include($this->pathView('_contents')); ?>

</div>

<?php require_once('footer.php'); ?>
