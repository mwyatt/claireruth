<div class="content-<?php echo $rowContent['type']; ?>" data-id="<?php echo $rowContent['id']; ?>">
	<h2 class="content-<?php echo $rowContent['type']; ?>-title"><a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $rowContent['id']; ?>"><?php echo $rowContent['title']; ?></a></h2>
	<span class="content-<?php echo $rowContent['type']; ?>-date"><?php echo date('d/m/Y', $rowContent['date_published']); ?></span>

<?php include($this->pathView('partial/content-tags')); ?>
<?php if (array_key_exists('media', $rowContent)): ?>
	<?php $media = current($rowContent['media']) ?>
	
	<a href="<?php echo $media['guid'] ?>" class="content-<?php echo $media['type']; ?>-thumb">
		<img src="<?php echo $media['thumb_150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<span class="content-<?php echo $rowContent['type']; ?>-author"><?php echo $rowContent['user_name']; ?></span>
	<span class="content-<?php echo $rowContent['type']; ?>-status"><?php echo $rowContent['status']; ?></span>
	<div class="content-actions">
		<a href="<?php echo $rowContent['guid']; ?>" title="View <?php echo $rowContent['title']; ?> online" target="blank">View</a>
		<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $rowContent['id']; ?>" title="Edit <?php echo $rowContent['title']; ?>" class="edit">Edit</a>
		<a href="<?php echo $this->url('current_noquery'); ?>?delete=<?php echo $rowContent['id']; ?>" title="Delete <?php echo $rowContent['title']; ?>" class="delete">Delete</a>
	</div>
</div>
