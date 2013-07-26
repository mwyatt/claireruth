<?php require_once($this->pathView() . 'header.php'); ?>

<article class="content page">
	<header>
		<h1><?php echo $this->get('model_maincontent', 'title') ?></h1>
		<span class="date"><?php echo date('D jS F Y', $this->get('model_maincontent', 'date_published')) ?></span>
	</header>
	<section>
		<?php echo $this->get('model_maincontent', 'html') ?>
	</section>
	<footer>
		Share this page: <strong>twitter</strong> <strong>facebook</strong>
	</footer>
</article>

<?php require_once($this->pathView() . 'footer.php'); ?>