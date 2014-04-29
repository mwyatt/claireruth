<div class="media-browser js-media-browser">
	<div class="attached media-browser-attached js-media-attached clearfix <?php echo ($media ? '' : 'is-empty') ?>">
	    <p class="media-browser-attached-message text-center">Click files in the browser below to attach.</p>

<?php include($this->pathView('_media')) ?>

	</div>
	<div class="row">
    	<div class="media-search columns six">
    		<input type="text" placeholder="Search" class="js-media-search-input">
    	</div>
    	<div class="upload columns six js-form-upload js-is-hidden-while-searching">
    	    <label for="form_images">Upload Media</label>
    	    <input id="form_images" class="js-media-input-upload right" type="file" name="media[]" multiple />
    	    <progress class="upload-progress hidden clearfix" value="0" max="100"></progress>
    	</div>
	</div>
	<div class="media-browser-directory js-media-browser-directory">
	</div>
</div>
