<div class="content-<?php echo $rowContent['type']; ?> <?php echo 'is-' . $rowContent['status'] ?>" data-id="<?php echo $rowContent['id']; ?>">
	<h2 class="content-<?php echo $rowContent['type']; ?>-title"><a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $rowContent['id']; ?>" class="content-<?php echo $rowContent['type'] ?>-link"><?php echo $rowContent['title']; ?></a></h2>
	<div class="content-action">
		<a class="content-action-link" href="<?php echo $rowContent['url']; ?>" title="View <?php echo $rowContent['title']; ?> online" target="blank">View</a>
		<a class="content-action-link" href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $rowContent['id']; ?>" title="Edit <?php echo $rowContent['title']; ?>" class="edit">Edit</a>
		<a class="content-action-link" href="<?php echo $this->url('current_noquery'); ?>?archive=<?php echo $rowContent['id']; ?>" title="Archive <?php echo $rowContent['title']; ?>" class="archive">Archive</a>
	</div>
	<span class="content-<?php echo $rowContent['type']; ?>-date"><?php echo date('d/m/Y', $rowContent['time_published']); ?></span>
	<span class="content-<?php echo $rowContent['type']; ?>-author"><?php echo $rowContent['user_name']; ?></span>
	<span class="content-<?php echo $rowContent['type']; ?>-status"><?php echo $rowContent['status']; ?></span>
</div>
