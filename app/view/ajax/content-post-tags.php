<?php if ($this->get('model_content_tag')): ?>
	<?php foreach ($this->get('model_content_tag') as $tag): ?>

<div class="tag"><?php echo $tag['name'] ?></div>

	<?php endforeach ?>
<?php endif ?>
