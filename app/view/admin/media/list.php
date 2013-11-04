<?php require_once($this->pathView('admin/_header')); ?>

<div class="content clearfix">
	<div class="js-media-upload-container">
		<input type="text" class="search-filter">
	    <input id="js-media-input-upload" type="file" name="media" multiple />
	</div>

<?php if ($medias = $this->get('model_media')): ?>
    <?php include($this->pathView('admin/_medias')); ?>
<?php endif ?>

    </div>
</div>

<?php require_once($this->pathView('admin/_footer')); ?>
