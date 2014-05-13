<?php require_once($this->pathView('admin/_header')) ?>

<div class="content <?php echo $this->url->getPathPart(2) ?>">
	<a href="<?php echo $this->url('back') ?>" class="button back">Back</a>
	<h1>Update <?php echo $tag['title'] ?></h1>
	<form method="post" enctype="multipart/form-data">
		<div class="row">	
			<label class="above" for="form_title">Title</label>
			<input id="form_title" class="required" type="text" name="title" maxlength="75" value="<?php echo $tag['title'] ?>" autofocus="autofocus">
		</div>			
		<div class="row">	
			<label class="above" for="form_description">Description</label>
			<input id="form_description" class="required" type="text" name="description" maxlength="75" value="<?php echo $tag['description'] ?>">
		</div>			
		<a href="#" class="submit button">Save</a>
		<input type="submit" name="update">
	</form>
</div>

<?php require_once($this->pathView('admin/_footer')) ?>
