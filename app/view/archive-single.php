<?php require_once('header.php'); ?>

<div class="content archive single">
	<a href="<?php echo $this->url('back') ?>" class="button back right">Back</a>
	<h1><?php echo ucfirst($this->get('model_ttarchive', 'title')) ?></h1>
	<section><?php echo $this->get('model_ttarchive', 'html'); ?></section>
</div>

<?php require_once('footer.php'); ?>
