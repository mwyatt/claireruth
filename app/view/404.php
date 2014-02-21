<?php require_once($this->pathView('_header')) ?>

<div class="content content-404">
	<h1 class="content-404-title">404 Not Found</h1>
	<p class="content-404-description">Sorry, whatever you were looking for, could not be found! Please return Home.</p>
	<a href="<?php echo $this->url() ?>" class="button primary home">Home</a>
</div>

<?php require_once($this->pathView('_footer')) ?>
