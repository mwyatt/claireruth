<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div id="content" class="league player">
	
	<h2>Upload Form</h2>
	<h2>Existing Media:</h2>

	<table width="100%" cellspacing="0" cellpadding="20" border="1">
		<tr>
			<th>Image</th>
			<th>Actions</th>
			<th>Title</th>
			<th>Slug</th>
			<th>Date Uploaded</th>
			<th>Alt Text</th>
			<th>Type</th>
			<th>Filename</th>
			<th>User ID</th>
		</tr>	
	<?php foreach ($media->getResult() as $media) : extract($media); ?>
		<tr data-id="<?php echo $id; ?>">
			<td><img width="100px" height="100px" src="<?php echo home_url('media/upload/').$filename; ?>"></td>
			<td><a class="button primary js-delete">Delete</a></td>
			<td><?php echo $title; ?></td>
			<td><?php echo $title_slug; ?></td>
			<td><?php echo $date_uploaded; ?></td>
			<td><?php echo $alt; ?></td>
			<td><?php echo $type; ?></td>
			<td><?php echo $filename; ?></td>
			<td><?php echo $user_id; ?></td>
		</tr>	
	<?php endforeach; ?>
	</table>

	<form id="upload" action="" method="post" enctype="multipart/form-data">

		<input type="hidden" name="form_upload" value="true">

		<div>
			<input type="file" name="media[]" multiple>
		</div>
		<div>
			<input type="submit" value="Upload" />
		</div>
	</form>

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'admin/footer.php'); ?>