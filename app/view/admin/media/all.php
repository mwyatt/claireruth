<div class="media-browser view-select js-media-browser">
	<div class="tabs">
		<span class="tab-select">Choose Files</span>
		<span class="tab-upload">Upload Files</span>
	</div>
    <div class="tab-select-content">
		<input type="text" class="search-filter">
		<span class="button attach">Attach</span>

<?php if ($rowContent['media'] = $this->get('model_media')): ?>
	<?php include($this->pathAdminView('partial/content-medias')); ?>
<?php endif ?>

	</div>
	<div class="tab-upload-content">
	    <input type="file" name="media" multiple />
	</div>
</div>
