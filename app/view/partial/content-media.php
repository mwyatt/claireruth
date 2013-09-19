<div class="content-media content-media-<?php echo $key ?>" data-id="<?php echo (array_key_exists('id', $row) ? $row['id'] : '') ?>">
	<div class="content-media-thumb">
		<img src="<?php echo (array_key_exists('thumb_150', $row) ? $row['thumb_150'] : '') ?>" alt="<?php echo (array_key_exists('title', $row) ? $row['title'] : '') ?>">
	</div>
	<p class="content-media-title"><?php echo (array_key_exists('title', $row) ? $row['title'] : '') ?></p>
	<span class="content-media-date-published"><?php echo (array_key_exists('date_published', $row) ? $row['date_published'] : '') ?></span>
	<span class="content-media-author"><?php echo (array_key_exists('user_full_name', $row) ? $row['user_full_name'] : '') ?></span>
	<span class="content-media-delete">&times;</span>
	<span class="content-media-tick"></span>
</div>
