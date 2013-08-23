<?php require_once('header.php'); ?>

<div class="content home clearfix">

<?php if (array_key_exists('model_maincontent', $this->data)): ?>
    
	<div class="posts">

    <?php foreach ($this->data['model_maincontent'] as $content): ?>
		<?php require($this->pathView() . 'entity/post.php'); ?>
    <?php endforeach ?>

	</div>

<?php endif ?>

</div>

<?php require_once('footer.php'); ?>