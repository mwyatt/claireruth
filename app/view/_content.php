<div class="content-<?php echo $rowContent['type']; ?>" data-id="<?php echo $rowContent['id']; ?>">

<?php if (array_key_exists('media', $rowContent)): ?>
	<?php $media = current($rowContent['media']) ?>
	<?php if (array_key_exists('thumb_150', $media)): ?>
		
	<a href="<?php echo $rowContent['url'] ?>" class="content-thumb">
		<img src="<?php echo $media['thumb_300'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

	<?php endif ?>
<?php endif ?>

	<h2 class="content-<?php echo $rowContent['type']; ?>-title"><a href="<?php echo $rowContent['url'] ?>" class="content-<?php echo $rowContent['type'] ?>-link"><?php echo $rowContent['title']; ?></a></h2>
	<div class="content-<?php echo $rowContent['type']; ?>-html">

<?php echo $rowContent['html']; ?>

	</div>
	<span class="content-<?php echo $rowContent['type']; ?>-date"><?php echo date('d/m/Y', $rowContent['time_published']); ?></span>

<?php include($this->pathView('_content-tags')); ?>

	<span class="content-<?php echo $rowContent['type']; ?>-author"><?php echo $rowContent['user_name']; ?></span>
</div>
