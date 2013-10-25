<?php if ($this->get('love')): ?>

<div class="love" data-id="<?php echo $contentMeta['content_id'] ?>">
	<span class="love-icon">
		<span class="love-count"><?php echo $contentMeta['value'] ?></span>
	</span>
</div>

<?php endif ?>
