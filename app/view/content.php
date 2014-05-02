<?php include($this->pathView('_header')) ?>

<div class="content <?php echo $firstContent->type ?>">

<?php include($this->pathView('_pagination')) ?>
<?php include($this->pathView('_contents')) ?>
<?php include($this->pathView('_pagination')) ?>

<!-- 	<div class="content-tags-all">
		<h3 class="tags-title">Tag Cloud:</h3> -->
		
<?php //$rowContent['tag'] = $this->get('model_tag') ?>
<?php //include($this->pathView('_content-tags')) ?>

	<!-- </div> -->

<?php //include($this->pathView('_months')) ?>

</div>

<?php include($this->pathView('_footer')) ?>
