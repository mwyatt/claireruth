<?php if ($this->get('model_tag')): ?>
	<?php foreach ($this->get('model_tag') as $tag): ?>

<div class="tag"><?php echo $tag['title'] ?></div>

	<?php endforeach ?>
<?php endif ?>
