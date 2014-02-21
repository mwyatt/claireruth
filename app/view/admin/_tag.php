<?php if ($tag): ?>
	
<div class="tag js-tag" data-id="<?php echo $tag['id'] ?>">
	<div class="tag-action">
		<a class="tag-action-link" href="<?php echo $this->url('current_noquery') ?>?edit=<?php echo $tag['id'] ?>" title="Edit <?php echo $tag['title'] ?>" class="edit">Edit</a>
		<a class="tag-action-link" href="<?php echo $this->url('current_noquery') ?>?delete=<?php echo $tag['id'] ?>" title="delete <?php echo $tag['title'] ?>" class="delete">delete</a>
	</div>
	<h3 class="tag-title"><a href="<?php echo $this->url('current_noquery') ?>?edit=<?php echo $tag['id'] ?>" class="tag-title-link"><?php echo $tag['title'] ?></a></h3>

	<?php if ($tag['description']): ?>
		
	<p class="tag-description"><?php echo $tag['description'] ?></p>

	<?php endif ?>
</div>

<?php endif ?>
