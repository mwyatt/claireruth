<article class="<?php echo $row['type']; ?>-single" data-id="<?php echo $row['id']; ?>">
	<h2 class="title"><?php echo $row['title']; ?></h2>
	<div class="description">

<?php echo $row['html']; ?>

	</div>
	<div class="date"><?php echo date('d/m/Y', $row['date_published']); ?></div>

<?php require($this->pathView() . 'entity/tags.php'); ?>
<?php if ($this->get($row, 'media')): ?>
	<?php $media = current($row['media']) ?>
	
	<a href="<?php echo $row['guid'] ?>" class="thumb">
		<img src="<?php echo $media['thumb_150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<div class="author"><?php echo $row['user_name']; ?></div>

</article>
