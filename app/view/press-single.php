<?php require_once('header.php'); ?>

<article class="content press single">
	<!-- <a href="<?php echo $this->url('back') ?>" class="button back">Back</a> -->
	<header>
		<h1><?php echo ucfirst($this->get('model_maincontent', 'title')) ?></h1>
		<span class="date"><?php echo date('D jS F Y', $this->get('model_maincontent', 'date_published')) ?></span>
	</header>
	<section><?php echo $this->get('model_maincontent', 'html'); ?></section>
	<footer>
		Share this press release: <strong>twitter</strong> <strong>facebook</strong>
	</footer>
</article>

<?php require_once('footer.php'); ?>
