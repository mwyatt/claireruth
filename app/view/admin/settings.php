<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div class="settings page">
	<h1><?php echo ucfirst($this->urlSegment(1)); ?></h1>
	<table class="main" width="100%" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th>Name</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>

<?php foreach ($this->get('model_mainoption') as $name => $value) : ?>
	<?php if ($name == 'meta_title'): ?>
			
			<tr>
				<td>
					<?php echo $name ?>
				</td>
				<td>
					<input type="text" name="<?php echo $name ?>" value="<?php echo $value ?>">
				</td>
			</tr>		

	<?php endif ?>
	<?php if ($name == 'meta_keywords'): ?>
			
			<tr>
				<td>
					<?php echo $name ?>
				</td>
				<td>
					<input type="text" name="<?php echo $name ?>" value="<?php echo $value ?>">
				</td>
			</tr>		

	<?php endif ?>
	<?php if ($name == 'meta_description'): ?>
			
			<tr>
				<td>
					<?php echo $name ?>
				</td>
				<td>
					<input type="text" name="<?php echo $name ?>" value="<?php echo $value ?>">
				</td>
			</tr>		

	<?php endif ?>
<?php endforeach; ?>

		</tbody>
	</table>
</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>