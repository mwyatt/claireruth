<?php require_once($this->pathView() . 'admin/_header.php'); ?>

<div class="content <?php echo $this->url(2); ?> <?php echo ($this->get('model_content') ? 'update' : 'create'); ?> content-create-update" data-id="<?php echo $this->get('model_content', 'id'); ?>">
	<!-- <a href="<?php echo $this->url('back') ?>" class="button back">Back</a> -->
	<h1 class="h3 mb1"><?php echo ($this->get('model_content') ? 'Update ' . ucfirst($this->url(2)) . ' ' . $this->get('model_content', 'title') : 'Create new ' . ucfirst($this->url(2))); ?></h1>
	<form class="main" method="post" enctype="multipart/form-data">
		<div class="row">	
			<label class="h5 block mb05" for="form_title">Title</label>
			<input id="form_title" class="required" type="text" name="title" maxlength="75" value="<?php echo $this->get('model_content', 'title'); ?>" autofocus="autofocus">
		</div>			

<?php if ($this->url(2) != 'minutes' && $this->url(2) != 'cup'): ?>
	<?php include($this->pathView('admin/content/_wysihtml5')); ?>
<?php endif ?>

		<div class="row media layout-media-5-col clearfix">	
			<label class="h5 block mb05"><a href="<?php echo $this->url() ?>admin/ajax/media/lightbox/" class="button primary js-lightbox-media-browser right">Attach files</a>Media</label>

<?php if ($medias = $this->get('model_content', 'media')): ?>
			
			<div class="attached layout-media-5-col">

	<?php include($this->pathView('admin/_medias')); ?>
	<?php foreach ($this->get('model_content', 'media') as $media): ?>

			<input type="hidden" name="media[]" value="<?php echo $media['id'] ?>">
		
	<?php endforeach ?>

			</div>
	
<?php endif ?>

		</div>
		<div class="row tag-manage-container">
			<label class="h5 block mb05" for="form-tag-search">Tag</label>
			<div class="tag-action">
				<input id="form-tag-search" class="search js-tag-input-search" type="text" name="tag_search" maxlength="100" value="" placeholder="Add tag">
				<div class="tag-drop js-tag-drop"></div>
			</div>
			<div class="tags js-tag-attached">
	
<?php if ($tags = $this->get('model_content', 'tag')): ?>
	<?php include($this->pathView('admin/_tags')); ?>
<?php endif ?>

			</div>
		</div>

<?php if ($contentStatus): ?>
	
		<div class="row">
			<label for="status">Status</label>
			<select name="status" id="status">

	<?php foreach ($contentStatus as $status): ?>

				<option value="<?php echo $status ?>"<?php echo ($this->get('model_content', 'status') == $status ? ' selected="selected"' : ''); ?>><?php echo ucfirst($status) ?></option>
		
	<?php endforeach ?>

			</select>
		</div>

<?php endif ?>

		<input name="<?php echo ($this->get('model_content') ? 'update' : 'create'); ?>" type="hidden" value="true">
		<input name="type" type="hidden" value="<?php echo $this->url(2); ?>">
		<a href="#" class="submit button">Save</a>
		<input type="submit">
	</form>
</div>

<?php require_once($this->pathView() . 'admin/_footer.php'); ?>
