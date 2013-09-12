<?php require_once('header.php'); ?>

<div class="content home clearfix">

<?php if (array_key_exists('model_maincontent', $this->data)): ?>
    
	<div class="posts">

    <?php foreach ($this->data['model_maincontent'] as $row): ?>
		<?php include($this->pathView('partial/content')); ?>
    <?php endforeach ?>

	</div>

<?php endif ?>

</div>

<?php require_once('footer.php'); ?>