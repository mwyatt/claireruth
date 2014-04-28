<?php require_once($this->pathView() . 'admin/_header.php') ?>

<div class="content <?php echo $this->url(2) ?> <?php echo ($content ? 'update' : 'create') ?> content-create-update" data-id="<?php echo $content->id ?>">

<?php if ($content->status == 'visible'): ?>
	
	<a href="<?php echo $this->buildUrl(array($content->type, $content->title)) ?>" class="button right" target="_blank">View</a>

<?php endif ?>

	<h1 class="h3 mb1"><?php echo ($content ? 'Update ' . ucfirst($this->url(2)) . ' ' . $content->title : 'Create new ' . ucfirst($this->url(2))) ?></h1>
	<form class="main" method="post" enctype="multipart/form-data">
		<div class="row">	
			<label class="h5 block mb05" for="form_title">Title</label>
			<input id="form_title" class="required" type="text" name="title" maxlength="75" value="<?php echo $content->title ?>" autofocus="autofocus">
		</div>			

<?php include($this->pathView('admin/content/_wysihtml5')) ?>

		<div class="row media layout-media-5-col clearfix">
			<h2 class="h2">Media</h2>

<?php $media = $content->media ?>
<?php include($this->pathView('admin/media/_browser')) ?>

		</div>
		<div class="row tag-manage-container clearfix">
			<input id="form-tag-search" class="search js-tag-input-search right" type="text" name="tag_search" maxlength="100" value="" placeholder="Tag Name">
			<div class="form-tag-drop js-form-tag-drop hidden"></div>
			<label class="h5 block mb05" for="form-tag-search">Tag</label>
			<div class="tags js-tag-attached">
	
<?php if ($tags = $content->tag): ?>
	<?php include($this->pathView('admin/_tags')) ?>
<?php endif ?>

			</div>
		</div>

<?php if ($contentStatus): ?>
	
		<div class="row clearfix">
			<label for="status">Status</label>
			<select name="status" id="status">

	<?php foreach ($contentStatus as $status): ?>

				<option value="<?php echo $status ?>"<?php echo ($content->status == $status ? ' selected="selected"' : '') ?>><?php echo ucfirst($status) ?></option>
		
	<?php endforeach ?>

			</select>
		</div>

<?php endif ?>

		<input name="<?php echo ($content ? 'update' : 'create') ?>" type="hidden" value="true">
		<input name="type" type="hidden" value="<?php echo $this->url(2) ?>">
		<a href="#" class="submit button">Save</a>
		<input type="submit">
	</form>
</div>

<?php require_once($this->pathView() . 'admin/_footer.php') ?>
