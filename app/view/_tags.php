<?php if ($tags): ?>
	
	<div class="tags">

	<?php foreach ($tags as $tag): ?>
		<?php include($this->pathView('_tag')) ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
