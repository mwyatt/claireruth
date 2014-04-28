<div class="media-browser js-media-browser">
	<div class="attached media-browser-attached js-media-attached clearfix <?php echo ($media ? '' : 'is-empty') ?>">
	    <p class="media-browser-attached-message text-center">Click files in the browser below to attach.</p>

	<?php include($this->pathView('_media')) ?>
	<?php if ($media): ?>
		<?php foreach ($media as $medium): ?>

		<input type="hidden" name="media[]" value="<?php echo $medium->id ?>">
		
		<?php endforeach ?>
	<?php endif ?>

	</div>
	<div class="row">
    	<div class="media-search columns four">
    		<input type="text" placeholder="Search" class="js-media-search-input">
    	</div>
    	<div class="columns four js-is-hidden-while-searching hidden">
			<input class="mr1 form-new-directory-input js-form-new-directory-input" type="text" placeholder="Folder Name">
			<a class="button submit form-new-directory-button js-form-new-directory-button">Create</a>
    	</div>
    	<div class="upload columns four js-form-upload js-is-hidden-while-searching">
    	    <label for="form_images">Upload Media</label>
    	    <input id="form_images" class="upload-input right" type="file" name="images" multiple />
    	    <progress class="upload-progress hidden clearfix" value="0" max="100"></progress>
    	</div>
	</div>
	<div class="media-browser-directory js-media-browser-directory">
	</div>
</div>
