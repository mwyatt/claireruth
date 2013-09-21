<?php if ($this->url(0) == 'admin'): ?>

<span class="content-<?php echo $rowContent['type']; ?>-status"><?php echo $rowContent['status']; ?></span>
<div class="content-actions">
	<a href="<?php echo $rowContent['guid']; ?>" title="View <?php echo $rowContent['title']; ?> online" target="blank">View</a>
	<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $rowContent['id']; ?>" title="Edit <?php echo $rowContent['title']; ?>" class="edit">Edit</a>
	<a href="<?php echo $this->url('current_noquery'); ?>?delete=<?php echo $rowContent['id']; ?>" title="Delete <?php echo $rowContent['title']; ?>" class="delete">Delete</a>
</div>

<?php endif ?>
