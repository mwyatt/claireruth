<?php require_once($this->pathView('_header')) ?>
<?php if ($this->get('model_content')): ?>
	<?php foreach ($this->get('model_content') as $content): ?>
	
<div class="content content-single <?php echo $content['type'] ?>-single" data-id="<?php echo $content['id'] ?>">
	<h1 class="main"><?php echo $content['title'] ?></h1>

<?php if ($rowContent = $this->get('model_content')): ?>
	<?php include($this->pathView('_content-medias')) ?>
<?php endif ?>

	<div class="content-<?php echo $content['type'] ?>-html"><?php echo $content['html'] ?></div>
	<span class="content-<?php echo $content['type'] ?>-date"><?php echo date('d/m/Y', $content['time_published']) ?></span>

<?php if ($rowContent = $this->get('model_content')): ?>
	<?php include($this->pathView('_content-tags')) ?>
<?php endif ?>

	<span class="content-<?php echo $content['type'] ?>-author"><?php echo $content['user_name'] ?></span>
</div>

	<?php endforeach ?>
<?php endif ?>
<?php require_once($this->pathView('_footer')) ?>
