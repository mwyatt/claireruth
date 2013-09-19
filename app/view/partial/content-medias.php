<?php if (array_key_exists('media', $row)): ?>
	
	<div class="content-<?php echo $row['type']; ?>-medias">

	<?php foreach ($row['media'] as $key => $media): ?>
		<?php require($this->pathView('partial/content-media')); ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
