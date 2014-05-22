<?php $theDate = date('jS', $content->time_published) . ' of ' . date('F o', $content->time_published) ?>

<div class="content element is-type-<?php echo $content->type ?> clearfix<?php echo ($content->media ? ' has-thumb' : '') ?>" data-id="<?php echo $content->id ?>">

<?php $medium = reset($content->media) ?>
<?php if ($medium): ?>
	
	<div class="content-thumb-background" style="background-image: url(<?php echo $this->getPathMediaUpload($medium->path) ?>); background-repeat: no-repeat; background-position: center center;"></div>

<?php endif ?>
<?php include($this->pathView('_medium')) ?>

	<div class="content-date" title="<?php echo $theDate ?>"><?php echo $theDate ?></div>
	<h2 class="content-title"><a href="<?php echo $this->url->build(array($content->type, $content->slug)) ?>" class="content-link"><?php echo $content->title ?></a></h2>
	<div class="content-html">

<?php echo $content->html ?>

	</div>
	<div class="content-status">

<?php echo ucfirst($content->status) ?>

	</div>

<?php $tags = $content->tag ?>
<?php include($this->pathView('_tags')) ?>

	<div class="content-author"><?php // echo $content->user_name ?></div>

<?php if ($this->isAdmin()): ?>
	
	<div class="content-action">
		<a class="content-action-link" href="<?php // echo $content->url ?>" title="View <?php echo $content->title ?> online" target="blank">View</a>
		<a class="content-action-link" href="<?php echo $this->url('current_noquery') ?>?edit=<?php echo $content->id ?>" title="Edit <?php echo $content->title ?>" class="edit">Edit</a>
		<a class="content-action-link" href="<?php echo $this->url('current_noquery') ?>?<?php echo ($content->status == 'archive' ? 'delete' : 'archive') ?>=<?php echo $content->id ?>" title="<?php echo ($content->status == 'archive' ? 'Delete' : 'Archive') ?> <?php echo $content->title ?>" class="archive"><?php echo ($content->status == 'archive' ? 'Delete' : 'Archive') ?></a>
	</div>

<?php endif ?>

</div>
