<?php require_once($this->pathView('admin/_header')) ?>

<div class="content clearfix">
	<div class="js-media-upload-container mb1">
	    <input class="js-media-input-upload" type="file" name="media" multiple />
	    <progress class="js-media-progress" max="100" value="0"></progress>
	</div>
	<div class="js-media-refresh layout-media-5-col">

<?php if ($medias = $this->get('model_media')): ?>
    <?php include($this->pathView('admin/_medias')) ?>
<?php else: ?>
	
	<div class="nothing-yet">
		<p>No <?php echo ucfirst($this->url->getPathPart(2)) ?> have been created yet, why not <a href="<?php echo $this->url('current_noquery') ?>new/">create</a> one now?</p>
	</div>
	
<?php endif ?>

    </div>
</div>

<?php require_once($this->pathView('admin/_footer')) ?>
