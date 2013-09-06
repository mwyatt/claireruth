<?php if (array_key_exists('media', $row)): ?>
	
	<div class="medias">

	<?php foreach ($row['media'] as $media): ?>
		<?php require($this->pathView() . 'entity/media.php'); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
