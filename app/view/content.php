<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content <?php echo $this->get('first_content', 'type') ?>">
	<h1>All <?php echo $this->get('first_content', 'type') . (count($this->get('model_content')) > 1 ? 's' : '') ?></h1>	

<?php $rowContents = $this->get('model_content') ?>
<?php include($this->pathView() . 'partial/contents.php'); ?>

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>
