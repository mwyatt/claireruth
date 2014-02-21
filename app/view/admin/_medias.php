<?php if ($medias): ?>
	
	<div class="medias">

	<?php foreach ($medias as $key => $media): ?>
		<?php require($this->pathView('admin/_media')) ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
