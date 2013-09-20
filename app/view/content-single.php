<?php require_once($this->pathView() . 'header.php') ?>

<div class="content <?php echo $this->get('model_content', 'type') ?>-single" data-id="<?php echo $this->get('model_content', 'id') ?>">
	<h1><?php echo $this->get('model_content', 'title') ?></h1>	
	<a href="<?php echo $this->get('model_content', 'guid') ?>" class="content-<?php echo $this->get('model_content', 'type') ?>-link">Permalink</a>
	<div class="content-<?php echo $this->get('model_content', 'type') ?>-html">

<?php echo $this->get('model_content', 'html') ?>

	</div>
	<span class="content-<?php echo $this->get('model_content', 'type') ?>-date"><?php echo date('d/m/Y', $this->get('model_content', 'date_published')) ?></span>

<?php if ($rowContent = $this->get('model_content')): ?>
	<?php include($this->pathView('partial/content-tags')) ?>
	<?php include($this->pathView('partial/content-medias')) ?>
<?php endif ?>

	<span class="content-<?php echo $this->get('model_content', 'type') ?>-author"><?php echo $this->get('model_content', 'user_name') ?></span>
</div>

<?php require_once($this->pathView() . 'footer.php') ?>
