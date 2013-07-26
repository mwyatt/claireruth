<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content post">
	
	<h1>Press Releases</h1>

	<?php if ($mainContent->getData()) : ?>	
		<?php while ($mainContent->nextRow()) : ?>

	<article>
		<header>
			<h2><a href="<?php echo $mainContent->getRow('guid'); ?>" title="Open article"><?php echo $mainContent->getRow('title'); ?></a></h2>
		</header>
		
		<p><?php echo substr($mainContent->getRow('html'), 0, 100); ?></p>
		<footer>
			<time><?php echo date('d/m/Y', $mainContent->getRow('date_fulfilled')); ?></time>
			<a href="<?php echo $mainContent->getRow('guid'); ?>">Read More</a>
		</footer>
	</article>

		<?php endwhile; ?>
	<?php endif; ?>	

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'footer.php'); ?>