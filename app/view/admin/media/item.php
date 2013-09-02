<div class="item" data-id="<?php echo (array_key_exists('id', $row) ? $row['id'] : '') ?>">
	<p class="thumb">
		<img src="<?php echo (array_key_exists('path', $row) ? $row['path'] : '') ?>" alt="<?php echo (array_key_exists('title', $row) ? $row['title'] : '') ?>">
	</p>
	<p class="title"><?php echo (array_key_exists('title', $row) ? $row['title'] : '') ?></p>
	<span class="date-published"><?php echo (array_key_exists('date_published', $row) ? $row['date_published'] : '') ?></span>
	<span class="author"><?php echo (array_key_exists('user_full_name', $row) ? $row['user_full_name'] : '') ?></span>
	<span class="delete">&times;</span>
	<span class="tick"></span>
</div>
