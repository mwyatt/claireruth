<?php if ($rowContents): ?>
	
	<div class="contents">

	<?php foreach ($rowContents as $rowContent): ?>
		<?php require($this->pathView('partial/content')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
