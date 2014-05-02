<?php include($this->pathView('_header')) ?>

<div class="content tag">
	<h1>All <?php echo $firstContent->type ?>s tagged <?php echo $tagCurrent->title ?></h1>

<?php include($this->pathView('_contents')) ?>

</div>

<?php include($this->pathView('_footer')) ?>
