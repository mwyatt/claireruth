<?php require_once('_header.php') ?>

<div class="content search">
	<!-- <h1 class="core-h1">Search results</h1> -->
	<p class="search-result-description">You searched for "<?php echo $query ?>". Which returned <?php echo $resultCount ?> result<?php echo (count($contents) > 1 || ! $contents ? 's' : '') ?>.</p>

<?php include($this->pathView('_pagination')) ?>
<?php if ($contents) : ?>

	<div class="search-results">

	<?php include($this->pathView('_contents')) ?>

	</div>

<?php endif ?>	
<?php include($this->pathView('_pagination')) ?>

</div>

<?php require_once('_footer.php') ?>
