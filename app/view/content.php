<?php $rowContents = $this->get('model_content') ?>
<?php $rowMonths = $this->get('content_month') ?>
<?php require_once($this->pathView() . '_header.php'); ?>

<div class="content <?php echo $this->get('first_content', 'type') ?>">
	<h1 class="main">All <?php echo $this->get('first_content', 'type') . (count($this->get('model_content')) > 1 ? 's' : '') ?></h1>	

<?php include($this->pathView('pagination')); ?>
<?php include($this->pathView() . '_contents.php'); ?>
<?php include($this->pathView('pagination')); ?>
<?php $rowContent['tag'] = $this->get('model_content_tag') ?>
<?php include($this->pathView('_content-tags')); ?>
<?php include($this->pathView('_months')); ?>

</div>

<?php require_once($this->pathView() . '_footer.php'); ?>
