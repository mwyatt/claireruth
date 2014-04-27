<div class="media-browser js-media-browser">
	<div class="attached media-browser-attached js-media-attached clearfix <?php echo ($media ? '' : 'is-empty') ?>">
	    <p class="media-browser-attached-message text-center">Click files in the browser below to attach.</p>

	<?php include($this->pathView('_media')) ?>
	<?php foreach ($media as $medium): ?>

		<input type="hidden" name="media[]" value="<?php echo $medium->id ?>">
		
	<?php endforeach ?>

	</div>
	<div class="media-browser-directory js-media-browser-directory">
	</div>
</div>