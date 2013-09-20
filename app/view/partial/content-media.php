<div class="content-media content-media-<?php echo $key ?>" data-id="<?php echo (array_key_exists('id', $rowMedia) ? $rowMedia['id'] : '') ?>">
	<div class="content-media-thumb">
		<img src="<?php echo (array_key_exists('thumb_150', $rowMedia) ? $rowMedia['thumb_150'] : '') ?>" alt="<?php echo (array_key_exists('title', $rowMedia) ? $rowMedia['title'] : '') ?>">
	</div>
	<p class="content-media-title"><?php echo (array_key_exists('title', $rowMedia) ? $rowMedia['title'] : '') ?></p>
	<span class="content-media-date-published"><?php echo (array_key_exists('date_published', $rowMedia) ? $rowMedia['date_published'] : '') ?></span>
	<span class="content-media-author"><?php echo (array_key_exists('user_full_name', $rowMedia) ? $rowMedia['user_full_name'] : '') ?></span>
	<span class="content-media-delete">&times;</span>
	<span class="content-media-tick"></span>
</div>
