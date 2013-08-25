<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div class="content <?php echo $this->urlSegment(2); ?> <?php echo ($this->get('model_maincontent') ? 'update' : 'create'); ?>" data-id="<?php echo $this->get('model_maincontent', 'id'); ?>">
	<a href="<?php echo $this->url('back') ?>" class="button back">Back</a>
	<h1><?php echo ($this->get('model_maincontent') ? 'Update ' . ucfirst($this->urlSegment(2)) . ' ' . $this->get('model_maincontent', 'title') : 'Create new ' . ucfirst($this->urlSegment(2))); ?></h1>
	<form class="main" method="post" enctype="multipart/form-data">
		<div class="row">	
			<label class="above" for="form_title">Title</label>
			<input id="form_title" class="required" type="text" name="title" maxlength="75" value="<?php echo $this->get('model_maincontent', 'title'); ?>" autofocus="autofocus">
		</div>			

<?php if ($this->urlSegment(2) != 'minutes' && $this->urlSegment(2) != 'cup'): ?>

		<div class="row">
			<label class="above" for="form_html">Content</label>
			<div id="toolbar" class="toolbar clearfix" style="display: none;">
				<a class="button" data-wysihtml5-command="bold" title="CTRL+B">bold</a>
				<a class="button" data-wysihtml5-command="italic" title="CTRL+I">italic</a>
				<a class="button" data-wysihtml5-command="createLink">insert link</a>
				<a class="button" data-wysihtml5-command="insertImage">insert image</a>
				<a class="button" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1">h1</a>
				<a class="button" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2">h2</a>
				<a class="button" data-wysihtml5-command="insertUnorderedList">insertUnorderedList</a>
				<a class="button" data-wysihtml5-command="insertOrderedList">insertOrderedList</a>
				<a class="button" data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red">red</a>
				<a class="button" data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green">green</a>
				<a class="button" data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue">blue</a>
				<a class="button" data-wysihtml5-command="insertSpeech">speech</a>
				<a class="button" data-wysihtml5-action="change_view">switch to html view</a>
				<div data-wysihtml5-dialog="createLink" style="display: none;">
					<label>
						Link:
						<input data-wysihtml5-dialog-field="href" value="http://">
					</label>
					<a class="button" data-wysihtml5-dialog-action="save">OK</a>
					<a class="button" data-wysihtml5-dialog-action="cancel">Cancel</a>
				</div>
				<div data-wysihtml5-dialog="insertImage" style="display: none;">
					<label>
						Image:
						<input data-wysihtml5-dialog-field="src" value="http://">
					</label>
					<label>
						Align:
						<select data-wysihtml5-dialog-field="className">
							<option value="">default</option>
							<option value="wysiwyg-float-left">left</option>
							<option value="wysiwyg-float-right">right</option>
						</select>
					</label>
					<a class="button" data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a class="button" data-wysihtml5-dialog-action="cancel">Cancel</a>
				</div>
			</div>
			<textarea id="form_html" name="html"><?php echo $this->get('model_maincontent', 'html'); ?></textarea>
		</div>

<?php endif ?>

		<div class="row media">

<?php if (! $this->get('model_mainmedia') || $this->urlSegment(2) != 'minutes'): ?>

			<div class="row">
				<label class="above" for="form_attach">Attach a file</label>
				<input id="form_attach" type="file" name="media[]"<?php echo ($this->urlSegment(2) == 'minutes' || $this->urlSegment(2) == 'cup' ? '' : ' multiple') ?>>
			</div>

<?php endif ?>
<?php if ($this->get('model_mainmedia')): ?>

			<div class="attached">
				<label class="above">Attached Media</label>
				
	<?php foreach ($this->get('model_mainmedia') as $media): ?>
		
				<div><a href="<?php echo $this->get($media, 'guid') ?>" target="_blank"><?php echo $this->get($media, 'path') ?></a></div>

	<?php endforeach ?>

			</div>
	
<?php endif ?>

		</div>
		<div class="management-tag">
			<label class="above" for="form-tag-search">Tag</label>
			<div class="area">
				<input id="form-tag-search" class="search" type="text" name="tag_search" maxlength="100" value="" placeholder="Add tag">
			</div>
			<div class="tags">

<?php if ($this->get('model_maincontent', 'tag')): ?>
	<?php foreach ($this->get('model_maincontent', 'tag') as $tag): ?>
			
				<div class="tag" data-id="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></div>
				<input name="tag[]" type="hidden" value="<?php echo $tag['name'] ?>">
		
	<?php endforeach ?>
<?php endif ?>
	
			</div>
		</div>

		<div class="row">
			<label for="status">Show on website</label>
			<input id="status" type="checkbox" name="status" value="visible"<?php echo ($this->get('model_maincontent', 'status') == 'visible' ? ' checked' : ''); ?>>
		</div>
		<input name="form_<?php echo ($this->get('model_maincontent') ? 'update' : 'create'); ?>" type="hidden" value="true">
		<input name="type" type="hidden" value="<?php echo $this->urlSegment(2); ?>">
		<a href="#" class="submit button">Save</a>
		<input type="submit">
	</form>

	<a href="#x" class="overlay" id="login_form"></a>
    <div class="popup">
    	<p>example information within the modal</p>
        <a class="close" href="#close"></a>
    </div>
</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>
