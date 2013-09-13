<div class="content-<?php echo $row['type']; ?>" data-id="<?php echo $row['id']; ?>">
	<h2 class="content-<?php echo $row['type']; ?>-title"><a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h2>
	<span class="content-<?php echo $row['type']; ?>-date"><?php echo date('d/m/Y', $row['date_published']); ?></span>

<?php include($this->pathView('partial/content-tags')); ?>
<?php if (array_key_exists('media', $row)): ?>
	<?php $media = current($row['media']) ?>
	
	<a href="<?php echo $media['guid'] ?>" class="content-<?php echo $media['type']; ?>-thumb">
		<img src="<?php echo $media['thumb_150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<span class="content-<?php echo $row['type']; ?>-author"><?php echo $row['user_name']; ?></span>
	<span class="content-<?php echo $row['type']; ?>-status"><?php echo $row['status']; ?></span>
	<div class="content-actions">
		<a href="<?php echo $row['guid']; ?>" title="View <?php echo $row['title']; ?> online" target="blank">View</a>
		<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $row['id']; ?>" title="Edit <?php echo $row['title']; ?>" class="edit">Edit</a>
		<a href="<?php echo $this->url('current_noquery'); ?>?delete=<?php echo $row['id']; ?>" title="Delete <?php echo $row['title']; ?>" class="delete">Delete</a>
	</div>
</div>
