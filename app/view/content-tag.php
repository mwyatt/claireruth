<?php include($this->pathView('_header')) ?>

<div class="content tag">
	<h1>All <?php echo $this->get('content_first', 'type') ?>s tagged <?php echo $this->get('tag_name') ?></h1>

<?php $rowContents = $this->get('model_content') ?>
<?php include($this->pathView() . '_contents.php') ?>

</div>

<?php include($this->pathView('_footer')) ?>
