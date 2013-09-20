<?php if ($this->url(0) == 'admin'): ?>

<span class="content-<?php echo $row['type']; ?>-status"><?php echo $row['status']; ?></span>
<div class="content-actions">
	<a href="<?php echo $row['guid']; ?>" title="View <?php echo $row['title']; ?> online" target="blank">View</a>
	<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $row['id']; ?>" title="Edit <?php echo $row['title']; ?>" class="edit">Edit</a>
	<a href="<?php echo $this->url('current_noquery'); ?>?delete=<?php echo $row['id']; ?>" title="Delete <?php echo $row['title']; ?>" class="delete">Delete</a>
</div>

<?php endif ?>
