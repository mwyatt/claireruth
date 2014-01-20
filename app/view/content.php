<?php require_once($this->pathView() . '_header.php'); ?>
<?php $rowContents = $this->get('model_content') ?>
<?php $rowMonths = $this->get('content_month') ?>

<div class="content <?php echo $this->get('first_content', 'type') ?>">
	<!-- <h1 class="main">All <?php echo $this->get('first_content', 'type') . (count($this->get('model_content')) > 1 ? 's' : '') ?></h1>	 -->

<?php include($this->pathView('_pagination')); ?>
<?php include($this->pathView() . '_contents.php'); ?>
<?php include($this->pathView('_pagination')); ?>

	<div class="content-tags-all">
		<h3 class="tags-title">Tag Cloud:</h3>
		
<?php $rowContent['tag'] = $this->get('model_tag') ?>
<?php include($this->pathView('_content-tags')); ?>

	</div>

<?php include($this->pathView('_months')); ?>

</div>

<?php require_once($this->pathView() . '_footer.php'); ?>
