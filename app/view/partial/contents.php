<?php if ($this->get('row_contents')): ?>
	
	<div class="contents">

	<?php foreach ($this->get('row_contents') as $rowContent): ?>
		<?php require($this->pathView('partial/content')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
