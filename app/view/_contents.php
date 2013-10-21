<?php if ($rowContents): ?>
	
	<div class="contents clearfix">

	<?php foreach ($rowContents as $rowContent): ?>
		<?php require($this->pathView('_content')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
