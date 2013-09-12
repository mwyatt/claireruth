<?php if (array_key_exists('tag', $row)): ?>
	
	<div class="tags">

	<?php foreach ($row['tag'] as $tag): ?>
		<?php include($this->pathView('partial/content-tag')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
