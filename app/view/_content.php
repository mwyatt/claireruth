<?php $theDate = date('jS', $content->time_published) . ' of ' . date('F o', $content->time_published) ?>

<div class="content-<?php echo $content->type ?> clearfix<?php echo ($content->media ? ' has-thumb' : '') ?>" data-id="<?php echo $content->id ?>">

<?php if ($content->media): ?>

<?php endif ?>

	<h2 class="content-<?php echo $content->type ?>-title h2 mt0"><a href="<?php echo $this->buildUrl(array($content->type, $content->title)) ?>" class="content-<?php echo $content->type ?>-link"><?php echo $content->title ?></a></h2>
	<div class="content-<?php echo $content->type ?>-html">

<?php echo $content->html ?>

	</div>
	<span class="content-<?php echo $content->type ?>-date" title="<?php echo $theDate ?>"><?php echo $theDate ?></span>

<?php $tags = $content->tag ?>
<?php include($this->pathView('_tags')) ?>

	<span class="content-<?php echo $content->type ?>-author"><?php // echo $content->user_name ?></span>

<?php if ($this->isAdmin()): ?>
	
	<div class="content-action">
		<a class="content-action-link" href="<?php // echo $content->url ?>" title="View <?php echo $content->title ?> online" target="blank">View</a>
		<a class="content-action-link" href="<?php echo $this->url('current_noquery') ?>?edit=<?php echo $content->id ?>" title="Edit <?php echo $content->title ?>" class="edit">Edit</a>
		<a class="content-action-link" href="<?php echo $this->url('current_noquery') ?>?<?php echo ($content->status == 'archive' ? 'delete' : 'archive') ?>=<?php echo $content->id ?>" title="<?php echo ($content->status == 'archive' ? 'Delete' : 'Archive') ?> <?php echo $content->title ?>" class="archive"><?php echo ($content->status == 'archive' ? 'Delete' : 'Archive') ?></a>
	</div>

<?php endif ?>

</div>
