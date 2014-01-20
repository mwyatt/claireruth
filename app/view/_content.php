<div class="content-<?php echo $rowContent['type']; ?> clearfix<?php echo (array_key_exists('media', $rowContent) ? ' has-thumb' : '') ?>" data-id="<?php echo $rowContent['id']; ?>">

<?php if (array_key_exists('media', $rowContent)): ?>
	<?php $media = current($rowContent['media']) ?>
	<?php if (array_key_exists('thumb', $media)): ?>
		<?php if (array_key_exists('300', $media['thumb'])): ?>
		
	<a href="<?php echo $rowContent['url'] ?>" class="content-thumb">
		<img src="<?php echo $media['thumb']['300'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

		<?php endif ?>
	<?php endif ?>
<?php endif ?>

	<h2 class="content-<?php echo $rowContent['type']; ?>-title h2 mt0"><a href="<?php echo $rowContent['url'] ?>" class="content-<?php echo $rowContent['type'] ?>-link"><?php echo $rowContent['title']; ?></a></h2>
	<div class="content-<?php echo $rowContent['type']; ?>-html">

<?php echo $rowContent['html']; ?>

	</div>
	<span class="content-<?php echo $rowContent['type']; ?>-date" title="<?php echo date('jS', $rowContent['time_published']) . ' of ' . date('F o', $rowContent['time_published']); ?>"><?php echo date('jS F', $rowContent['time_published']); ?></span>

<?php include($this->pathView('_content-tags')); ?>

	<span class="content-<?php echo $rowContent['type']; ?>-author"><?php echo $rowContent['user_name']; ?></span>
</div>
