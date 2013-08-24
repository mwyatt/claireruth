<?php if ($this->get('model_maincontent_tag')): ?>
	<?php foreach ($this->get('model_maincontent_tag') as $tag): ?>

<div class="tag"><?php echo $tag['name'] ?></div>

	<?php endforeach ?>
<?php endif ?>
