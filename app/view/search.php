<?php require_once('_header.php') ?>

<div class="content search">
	<!-- <h1 class="core-h1">Search results</h1> -->
	<p class="search-result-description">You searched for "<?php echo $query ?>". Which returned <?php echo ($contents ? count($contents) : '0') ?> result<?php echo (count($contents) > 1 || ! $contents ? 's' : '') ?>.</p>

<?php if ($contents) : ?>

	<div class="search-results">

	<?php include($this->pathView('_contents')) ?>

	</div>

<?php endif ?>	

</div>

<?php require_once('_footer.php') ?>
