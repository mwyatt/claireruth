<div class="media-browser view-select">
	<div class="tabs">
		<span class="tab-select">Choose Files</span>
		<span class="tab-upload">Upload Files</span>
	</div>
    <div class="tab-select-content">
		<input type="text" class="search-filter">
		<span class="button attach">Attach</span>

<?php if ($this->get('model_mainmedia')): ?>
    
	<div class="media-items">

    <?php foreach ($this->get('model_mainmedia') as $row): ?>
		<?php require($this->pathView() . 'admin/media/item.php'); ?>
    <?php endforeach ?>

	</div>

<?php endif ?>

	</div>
	<div class="tab-upload-content">
	    <input type="file" name="media" multiple />
		
	</div>
</div>
