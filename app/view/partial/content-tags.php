<?php if (array_key_exists('tag', $rowContent)): ?>
	
	<div class="tags">

	<?php foreach ($rowContent['tag'] as $rowTag): ?>
		<?php include($this->pathView('partial/content-tag')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
