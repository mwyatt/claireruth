<?php if (array_key_exists('media', $row)): ?>
	
	<div class="content-<?php echo $row['type']; ?>-medias">

	<?php foreach ($row['media'] as $mediaKey => $media): ?>
		<?php require($this->pathView() . 'entity/media.php'); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
