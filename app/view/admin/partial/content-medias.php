<?php if (array_key_exists('media', $rowContent)): ?>
	
	<div class="content-medias">

	<?php foreach ($rowContent['media'] as $key => $rowMedia): ?>
		<?php require($this->pathAdminView('partial/content-media')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>