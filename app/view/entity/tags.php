<?php if (array_key_exists('tag', $row)): ?>
	
	<div class="tags">

	<?php foreach ($row['tag'] as $tag): ?>
		<?php require($this->pathView() . 'entity/tag.php'); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
