<article class="<?php echo $row['type']; ?>" data-id="<?php echo $row['id']; ?>">
	<h2 class="post-title"><?php echo $row['title']; ?></h2>
	<div class="post-description">

<?php echo $row['html']; ?>

	</div>
	<div class="post-date"><?php echo date('d/m/Y', $row['date_published']); ?></div>

<?php require($this->pathView() . 'entity/tags.php'); ?>
<?php if (array_key_exists('media', $row)): ?>
	<?php $media = current($row['media']) ?>
	
	<a href="<?php echo $row['guid'] ?>" class="post-thumb">
		<img src="<?php echo $media['thumb_150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<div class="post-author"><?php echo $row['user_name']; ?></div>

</article>
