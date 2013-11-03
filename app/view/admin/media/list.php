<?php require_once($this->pathView('admin/_header')); ?>

<div class="media-browser view-select js-media-browser">
	<input type="text" class="search-filter">
    <input class="" type="file" name="media" multiple />

<?php if ($medias = $this->get('model_media')): ?>
    <?php include($this->pathView('admin/_medias')); ?>
<?php endif ?>

    </div>
</div>

<?php require_once($this->pathView('admin/_footer')); ?>
