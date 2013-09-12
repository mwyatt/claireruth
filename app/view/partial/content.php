<div class="content-<?php echo $row['type']; ?>" data-id="<?php echo $row['id']; ?>">
	<h2 class="content-<?php echo $row['type']; ?>-title"><?php echo $row['title']; ?></h2>
	<div class="content-<?php echo $row['type']; ?>-html">

<?php echo $row['html']; ?>

	</div>
	<span class="content-<?php echo $row['type']; ?>-date"><?php echo date('d/m/Y', $row['date_published']); ?></span>

<?php include($this->pathView('partial/content-tags')); ?>
<?php if (array_key_exists('media', $row)): ?>
	<?php $media = current($row['media']) ?>
	
	<a href="<?php echo $media['guid'] ?>" class="content-<?php echo $media['type']; ?>-thumb">
		<img src="<?php echo $media['thumb_150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<span class="content-<?php echo $row['type']; ?>-author"><?php echo $row['user_name']; ?></span>

<?php if ($this->url(0) == 'admin'): ?>
	
	<span class="content-<?php echo $row['type']; ?>-status"><?php echo $row['status']; ?></span>

<?php endif ?>

</div>
