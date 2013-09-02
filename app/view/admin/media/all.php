<div class="media-browser view-select">
	<div class="tabs">
		<span class="tab-select">Choose Files</span>
		<span class="tab-upload">Upload Files</span>
	</div>
	<input type="text" class="search-filter">
	<span class="button attach">Attach</span>
	<div class="upload">
	    <label for="form_images">Upload Media</label>
	    <input id="form_images" type="file" name="images" multiple />
	    <div id="response"></div>
	    <ul id="image-list"></ul>
	</div>

<?php if (array_key_exists('model_mainmedia', $this->data)): ?>
    
	<div class="media-items">

    <?php foreach ($this->data['model_mainmedia'] as $row): ?>
		<?php require($this->pathView() . 'admin/media/item.php'); ?>
    <?php endforeach ?>

	</div>

<?php endif ?>

</div>
