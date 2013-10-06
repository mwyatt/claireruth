<?php $rowContents = $this->get('model_content') ?>
<?php $rowMonths = $this->get('month') ?>
<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content <?php echo $this->get('first_content', 'type') ?>">
	<h1 class="main">All <?php echo $this->get('first_content', 'type') . (count($this->get('model_content')) > 1 ? 's' : '') ?></h1>	

<?php include($this->pathView('pagination')); ?>
<?php include($this->pathView() . '_contents.php'); ?>
<?php include($this->pathView('pagination')); ?>
<?php include($this->pathView('_months')); ?>

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>
