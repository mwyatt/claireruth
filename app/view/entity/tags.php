<?php if (array_key_exists('tag', $content)): ?>
	
	<div class="tags">

	<?php foreach ($content['tag'] as $tag): ?>
		<?php require($this->pathView() . 'entity/tag.php'); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
