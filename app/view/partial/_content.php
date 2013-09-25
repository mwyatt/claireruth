<div class="content-<?php echo $rowContent['type']; ?>" data-id="<?php echo $rowContent['id']; ?>">
	<h2 class="content-<?php echo $rowContent['type']; ?>-title"><a href="<?php echo $rowContent['guid'] ?>" class="content-<?php echo $rowContent['type'] ?>-link"><?php echo $rowContent['title']; ?></a></h2>
	<div class="content-<?php echo $rowContent['type']; ?>-html">

<?php echo $rowContent['html']; ?>

	</div>
	<span class="content-<?php echo $rowContent['type']; ?>-date"><?php echo date('d/m/Y', $rowContent['date_published']); ?></span>

<?php include($this->pathView('partial/content-tags')); ?>
<?php if (array_key_exists('media', $rowContent)): ?>
	<?php $media = current($rowContent['media']) ?>
	
	<a href="<?php echo $rowContent['guid'] ?>" class="content-<?php echo $media['type']; ?>-thumb">
		<img src="<?php echo $media['thumb_150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<span class="content-<?php echo $rowContent['type']; ?>-author"><?php echo $rowContent['user_name']; ?></span>

<?php include($this->pathView('admin/partial-extension/content')); ?>
	
</div>
