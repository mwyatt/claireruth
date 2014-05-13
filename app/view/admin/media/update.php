<?php require_once($this->pathView('admin/_header')) ?>
<?php $media = $this->get('model_media') ?>

<div class="content <?php echo $this->url->getPathPart(2) ?>">
	<a href="<?php echo $this->url('back') ?>" class="button back">Back</a>
	<h1>Update <?php echo $this->get('model_media', 'title') ?></h1>
	<form method="post" enctype="multipart/form-data">

<?php if (array_key_exists('thumb', $media) && array_key_exists('150', $media['thumb'])): ?>

		<div class="row">
			<img src="<?php echo $media['thumb']['150'] ?>" alt="<?php echo $this->get('model_media', 'title') ?>">
		</div>
	
<?php endif ?>

		<div class="row">	
			<label class="above" for="form_title">Title</label>
			<input id="form_title" class="required" type="text" name="title" maxlength="75" value="<?php echo $this->get('model_media', 'title') ?>" autofocus="autofocus">
		</div>			
		<div class="row">	
			<label class="above" for="form_description">Description</label>
			<input id="form_description" class="required" type="text" name="description" maxlength="75" value="<?php echo $this->get('model_media', 'description') ?>">
		</div>			


		<div class="row management-tag">
			<label class="above" for="form-tag-search">Tag</label>
			<div class="area">
				<input id="form-tag-search" class="search" type="text" name="tag_search" maxlength="100" value="" placeholder="Add tag">
			</div>
			<div class="tags">
	
<?php if ($rowContent['tag'] = $this->get('model_media', 'tag')): ?>
	<?php include($this->pathView('_content-tags')) ?>
	<?php foreach ($this->get('model_media', 'tag') as $tag): ?>
			
			<input name="tag[]" type="hidden" value="<?php echo $tag['id'] ?>">
		
	<?php endforeach ?>
<?php endif ?>

			</div>
		</div>





		<a href="#" class="submit button">Save</a>
		<input type="submit" name="update">
	</form>
</div>

<?php require_once($this->pathView('admin/_footer')) ?>
