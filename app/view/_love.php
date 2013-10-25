<?php if ($this->get('model_content_meta')): ?>
	<?php foreach ($this->get('model_content_meta') as $row): ?>
		
<div class="love" data-content-id="<?php echo $row['content_id'] ?>">
	<span class="love-icon">
		<span class="love-count"><?php echo $row['value'] ?></span>
	</span>
</div>

	<?php endforeach ?>
<?php endif ?>
