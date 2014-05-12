<?php require_once($this->pathView('_header')) ?>
<?php if ($contents): ?>
	<?php foreach ($contents as $content): ?>
		
<div class="content-single is-type-<?php echo $content->type ?> clearfix" data-id="<?php echo $content->id ?>">
	<div class="content-date"><?php echo date('jS', $content->time_published) . ' of ' . date('F o', $content->time_published) ?></div>
	<h1 class="h1 content-single-title"><?php echo $content->title ?></h1>

		<?php $medium = reset($content->media) ?>
		<?php include($this->pathView('_medium')) ?>

	<div class="content-html typography"><?php echo $content->html ?></div>

		<?php $tags = $content->tag ?>
		<?php include($this->pathView('_tags')) ?>

	<div class="content-date"><?php echo date('jS', $content->time_published) . ' of ' . date('F o', $content->time_published) ?></div>
</div>

	<?php endforeach ?>
<?php endif ?>
<?php require_once($this->pathView('_footer')) ?>
