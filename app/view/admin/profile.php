<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div class="content profile update">
	<h1>Profile</h1>
	<form class="main" method="post">
		<div class="row">		
			<label class="above" for="form-first-name">First name</label>
			<input id="form-first-name" class="required" type="text" name="first_name" maxlength="75" value="<?php echo $this->get('model_mainuser', 'first_name'); ?>">
		</div>			
		<div class="row">
			<label class="above" for="form-last-name">Last name</label>
			<input id="form-last-name" class="required" type="text" name="last_name" maxlength="75" value="<?php echo $this->get('model_mainuser', 'last_name'); ?>">
		</div>
		<div class="row">			
			<label class="above" for="form-email">Email address (username)</label>
			<input id="form-email" class="required" type="text" name="email" maxlength="75" value="<?php echo $this->get('model_mainuser', 'email'); ?>">
		</div>			
		<div class="row">			
			<label class="above" for="form-password">New password</label>
			<input id="form-password" class="required" type="text" name="password" maxlength="75" value="">
		</div>			
		<div class="row">			
			<h2>Access level</h2>
			<p><?php echo $this->get('model_mainuser', 'level'); ?></p>
		</div>			
 		<div class="row">			
			<h2>Date registered</h2>
			<p><?php echo date('D jS F Y', $this->get('model_mainuser', 'date_registered')); ?></p>
		</div>			
		<input name="form_update" type="hidden" value="true">
		<a href="#" class="submit button">Save</a>
		<input type="submit">
	</form>
</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>