<?php include($this->pathView('_header')) ?>

<div class="content tag">
	<h1 class="content-single-title"><?php echo $totalContents ?> <?php echo $firstContent->type . $this->appendS($contents) ?> tagged '<?php echo $tagCurrent->title ?>'</h1>

<?php include($this->pathView('_pagination')) ?>
<?php include($this->pathView('_contents')) ?>
<?php include($this->pathView('_pagination')) ?>

</div>

<?php include($this->pathView('_footer')) ?>
