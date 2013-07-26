<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content post single">
	
	<h1><?php echo $mainContent->get('title'); ?></h1>

	<article>
		<header>
			<time><?php echo date('d/m/Y', $mainContent->get('date_fulfilled')); ?></time>
		</header>
		<section>
			<?php echo $mainContent->get('html'); ?>
		</section>
		<footer>
		</footer>
	</article>

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'footer.php'); ?>