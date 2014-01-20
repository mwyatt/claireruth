<?php require_once($this->pathView('_header')) ?>
<?php if ($modelContent): ?>
	<?php foreach ($modelContent as $content): ?>
	
<div class="content content-single <?php echo $content['type'] ?>-single clearfix" data-id="<?php echo $content['id'] ?>">
	<h1 class="h1 content-single-title"><?php echo $content['title'] ?></h1>

		<?php if ($rowContent = $modelContent): ?>
			<?php include($this->pathView('_content-medias')) ?>
		<?php endif ?>

	<div class="content-<?php echo $content['type'] ?>-html typography"><?php echo $content['html'] ?></div>
	<span class="content-<?php echo $content['type'] ?>-date"><?php echo date('jS', $content['time_published']) . ' of ' . date('F o', $content['time_published']); ?></span>

		<?php if ($rowContent = $modelContent): ?>
			<?php include($this->pathView('_content-tags')) ?>
		<?php endif ?>
		<?php if (array_key_exists('user_name', $modelContent)): ?>
	
	<span class="content-<?php echo $content['type'] ?>-author"><span class="content-<?php echo $content['type'] ?>-author-prefix">By</span> <span class="content-<?php echo $content['type'] ?>-author-name bold"><?php echo $content['user_name'] ?></span></span>
	
		<?php endif ?>

</div>

	<?php endforeach ?>
<?php endif ?>
<?php require_once($this->pathView('_footer')) ?>
