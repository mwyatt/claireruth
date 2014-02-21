<?php if ($media): ?>

<div class="media media-<?php echo $key ?> js-media-item" data-id="<?php echo $media['id'] ?>">

<?php if (array_key_exists('thumb', $media) && array_key_exists('150', $media['thumb'])): ?>
	
	<a href="<?php echo $this->url('admin') ?>media/?edit=<?php echo $media['id'] ?>" class="media-thumb">
		<img src="<?php echo $media['thumb']['150'] ?>" alt="<?php echo $media['title'] ?>">
	</a>

<?php endif ?>

	<p class="media-title"><a href="<?php echo $this->url('admin') ?>media/?edit=<?php echo $media['id'] ?>" class="media-title-link"><?php echo $media['title'] ?></a></p>
	<span class="media-date-published"><?php echo date('d m y', $media['time_published']) ?></span>
	<span class="media-author"><?php echo $media['user_full_name'] ?></span>
	<a href="<?php echo $this->url('admin') ?>media/?delete=<?php echo $media['id'] ?>" class="media-delete js-media-delete">Delete</a>
</div>

<?php endif ?>
