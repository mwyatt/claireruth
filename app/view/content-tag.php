<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content tag">
	<h1>All content by this tag</h1>

<?php $rowContents = $this->get('model_content') ?>
<?php include($this->pathView() . '_contents.php'); ?>

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>
