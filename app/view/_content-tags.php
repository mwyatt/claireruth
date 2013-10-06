<?php if (array_key_exists('tag', $rowContent)): ?>
	
	<div class="content-tags">

	<?php foreach ($rowContent['tag'] as $rowTag): ?>
		<?php include($this->pathView('_content-tag')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
