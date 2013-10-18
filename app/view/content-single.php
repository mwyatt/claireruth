<?php require_once($this->pathView('_header.php')) ?>

<div class="content content-single <?php echo $this->get('model_content', 'type') ?>-single" data-id="<?php echo $this->get('model_content', 'id') ?>">
	<h1 class="main"><?php echo $this->get('model_content', 'title') ?></h1>
	<div class="content-<?php echo $this->get('model_content', 'type') ?>-html"><?php echo $this->get('model_content', 'html') ?></div>
	<span class="content-<?php echo $this->get('model_content', 'type') ?>-date"><?php echo date('d/m/Y', $this->get('model_content', 'date_published')) ?></span>

<?php if ($rowContent = $this->get('model_content')): ?>
	<?php include($this->pathView('_content-tags')) ?>
	<?php include($this->pathView('_content-medias')) ?>
<?php endif ?>

	<span class="content-<?php echo $this->get('model_content', 'type') ?>-author"><?php echo $this->get('model_content', 'user_name') ?></span>
</div>

<?php require_once($this->pathView('_footer.php')) ?>
