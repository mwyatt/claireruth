<?php if ($medium): ?>
	
<div class="medium js-medium" data-id="<?php echo ($medium->id ? $medium->id : '') ?>">
	<div class="medium-thumb">
		<img src="<?php echo $this->getPathMediaUpload($medium->path) ?>" alt="<?php echo $medium->title ?>" class="medium-thumb-image">
	</div>
	<p class="medium-title"><?php echo $medium->title ?></p>
	<span class="medium-date-published"><?php echo $medium->time_published ?></span>
	<span class="medium-delete js-medium-delete">&times;</span>
	<span class="medium-tick"></span>
</div>

<?php endif ?>
