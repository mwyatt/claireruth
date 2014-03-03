<div class="content-<?php echo $content->type ?> clearfix<?php echo ($content->media ? ' has-thumb' : '') ?>" data-id="<?php echo $content->id ?>">

<?php if ($content->media): ?>

<?php endif ?>

	<h2 class="content-<?php echo $content->type ?>-title h2 mt0"><a href="<?php echo $this->buildUrl(array($content->type, $content->title)) ?>" class="content-<?php echo $content->type ?>-link"><?php echo $content->title ?></a></h2>
	<div class="content-<?php echo $content->type ?>-html">

<?php echo $content->html ?>

	</div>
	<span class="content-<?php echo $content->type ?>-date" title="<?php echo date('jS', $content->time_published) . ' of ' . date('F o', $content->time_published) ?>"><?php echo date('jS F', $content->time_published) ?></span>

<?php $tags = $content->tag ?>
<?php include($this->pathView('_tags')) ?>

	<span class="content-<?php echo $content->type ?>-author"><?php // echo $content->user_name ?></span>
</div>
