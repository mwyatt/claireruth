<?php require_once($this->pathView('header')); ?>

<article class="content page">
	<header>
		<h1><?php echo $this->get('model_content', 'title') ?></h1>
		<span class="date"><?php echo date('D jS F Y', $this->get('model_content', 'date_published')) ?></span>
	</header>
	<section>
		<?php echo $this->get('model_content', 'html') ?>
	</section>
</article>

<?php require_once($this->pathView('footer')); ?>
