<?php if ($media): ?>

<div class="media media-<?php echo $key ?> js-media-item">

<?php if (array_key_exists('thumb', $media) && array_key_exists('150', $media['thumb'])): ?>
	
	<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $media['id']; ?>" class="media-thumb">
		<img src="<?php echo $media['thumb']['150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<p class="media-title"><a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $media['id']; ?>" class="media-title-link"><?php echo $media['title'] ?></a></p>
	<span class="media-date-published"><?php echo $media['time_published'] ?></span>
	<span class="media-author"><?php echo $media['user_full_name'] ?></span>
	<span class="media-delete js-media-delete">&times;</span>
	<span class="media-tick"></span>
</div>

<?php endif ?>
