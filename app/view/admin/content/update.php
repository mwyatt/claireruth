<?php require_once($this->pathView() . 'admin/_header.php') ?>

<div class="content <?php echo $this->url->getPathPart(2) ?> <?php echo ($content ? 'update' : 'create') ?> content-create-update" data-id="<?php echo $content->id ?>">


<?php if ($content->status == 'visible'): ?>
	
	<a href="<?php echo $this->url->build(array($content->type, $content->title)) ?>" class="button right" target="_blank">View</a>

<?php endif ?>

	<h1 class="h3 mb1"><?php echo ($content ? 'Update ' . ucfirst($this->url->getPathPart(2)) . ' ' . $content->title : 'Create new ' . ucfirst($this->url->getPathPart(2))) ?></h1>
	<form class="main" method="post" enctype="multipart/form-data">
		<div class="frame">
		    <div class="bit-2">
		        <div class="box">
		        	<label class="h5 block mb05" for="form_title">Title</label>
		        	<input id="form_title" class="w100 required js-input-title" type="text" name="title" maxlength="75" value="<?php echo $content->title ?>" autofocus="autofocus">
		        </div>
		    </div>
		    <div class="bit-2">
		        <div class="box">
		        	<label class="h5 block mb05" for="form_slug">Slug</label>
		        	<input id="form_slug" class="w100 required js-input-slug" type="text" name="slug" maxlength="75" value="<?php echo $content->slug ?>" autofocus="autofocus">
		        </div>
		    </div>
		</div>

<?php include($this->pathView('admin/content/_wysihtml5')) ?>

		<div class="clearfix">
			<h2 class="h2">Media</h2>

<?php $media = $content->media ?>
<?php include($this->pathView('admin/media/_browser')) ?>

		</div>
		<div class="clearfix">
			<h2 class="h2">Tags</h2>
	
<?php $tags = $content->tag ?>
<?php include($this->pathView('admin/tag/_browser')) ?>

		</div>

<?php if ($contentStatus): ?>
	
		<div class="clearfix">
			<label for="status">Status</label>
			<select name="status" id="status">

	<?php foreach ($contentStatus as $status): ?>

				<option value="<?php echo $status ?>"<?php echo ($content->status == $status ? ' selected="selected"' : '') ?>><?php echo ucfirst($status) ?></option>
		
	<?php endforeach ?>

			</select>
		</div>

<?php endif ?>

		<div class="clearfix">
			<label for="time_published">Time Published</label>
			<input name="time_published[date]" type="date" value="<?php echo $contentDate ?>">
			<input name="time_published[time]" type="time" value="<?php echo $contentTime ?>">
		</div>
		<div class="clearfix">
			<input name="type" type="hidden" value="<?php echo $this->url->getPathPart(2) ?>">
			<input name="<?php echo ($content ? 'update' : 'create') ?>" type="hidden" value="true">
			<span class="submit button right">Save</span>
			<input type="submit">
		</div>
	</form>
</div>

<?php require_once($this->pathView() . 'admin/_footer.php') ?>
