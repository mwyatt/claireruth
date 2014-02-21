<?php if ($contents): ?>
	
	<div class="contents clearfix">

	<?php foreach ($contents as $content): ?>
		<?php require($this->pathView('_content')) ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
