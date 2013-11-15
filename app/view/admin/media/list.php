<?php require_once($this->pathView('admin/_header')); ?>

<div class="content clearfix">
	<div class="js-media-upload-container">
		<input type="text" class="search-filter">
	    <input class="js-media-input-upload" type="file" name="media" multiple />
	    <progress class="js-media-progress" max="100" value="0"></progress>
	</div>
	<div class="js-media-refresh">

<?php if ($medias = $this->get('model_media')): ?>
    <?php include($this->pathView('admin/_medias')); ?>
<?php endif ?>

    </div>
</div>

<?php require_once($this->pathView('admin/_footer')); ?>
