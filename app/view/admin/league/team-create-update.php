<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div class="content <?php echo $this->urlSegment(2); ?> <?php echo ($this->get('model_ttteam') ? 'update' : 'create'); ?>" data-id="<?php echo $this->get('model_ttteam', 'id'); ?>">
	<a href="<?php echo $this->url('current_noquery') ?>" class="button back">Back</a>
	<h1><?php echo ($this->get('model_ttteam') ? 'Update ' . $this->urlSegment(2) . ' ' . $this->get('model_ttteam', 'name') : 'Create new ' . ucfirst($this->urlSegment(2))); ?></h1>
	<form class="main" method="post">
		<div class="row">
			<label class="above" for="form-name">Name</label>		
			<input id="form-name" class="required" type="text" name="name" maxlength="75" value="<?php echo $this->get('model_ttteam', 'name'); ?>">
		</div>			

<?php if ($this->get('model_ttdivision')): ?>

		<div class="row division">
			<label class="above" for="form-division">Division</label>
			<select id="form-division" name="division_id">
				<option value="0"></option>
				 
	<?php foreach ($this->get('model_ttdivision') as $division): ?>
		
				<option value="<?php echo $this->get($division, 'id'); ?>" <?php echo ($this->get($division, 'id') == $this->get('model_ttteam', 'division_id') ? 'selected' : false); ?>><?php echo $this->get($division, 'name'); ?></option>

	<?php endforeach ?>

			</select>
		</div>
	
<?php endif ?>

<?php if ($this->get('home_nights')): ?>

		<div class="row">
			<label class="above" for="form-home-night">Home Night</label>
			<select id="form-home-night" name="home_night">
				<option value="0"></option>
				 
	<?php foreach ($this->get('home_nights') as $key => $night): ?>
		
				<option value="<?php echo $key ?>" <?php echo ($key == $this->get('model_ttteam', 'home_night_id') ? 'selected' : false); ?>><?php echo $night ?></option>

	<?php endforeach ?>

			</select>
		</div>
	
<?php endif ?>

<?php if ($this->get('model_ttvenue')): ?>

		<div class="row venue">
			<label class="above" for="form-venue">Venue</label>
			<select id="form-venue" name="venue_id">
				<option value="0"></option>
				 
	<?php foreach ($this->get('model_ttvenue') as $venue): ?>
		
				<option value="<?php echo $this->get($venue, 'id'); ?>" <?php echo ($this->get($venue, 'id') == $this->get('model_ttteam', 'venue_id') ? 'selected' : false); ?>><?php echo $this->get($venue, 'name'); ?></option>

	<?php endforeach ?>

			</select>
		</div>
	
<?php endif ?>
<?php if ($this->get('secretaries')): ?>

		<div class="row secretary">
			<label class="above" for="form-secretary">Secretary</label>
			<select id="form-secretary" name="secretary_id">
				<option value="0"></option>
				 
	<?php foreach ($this->get('secretaries') as $secretary): ?>
		
				<option value="<?php echo $this->get($secretary, 'id'); ?>" <?php echo ($this->get($secretary, 'id') == $this->get('model_ttteam', 'secretary_id') ? 'selected' : false); ?>><?php echo $this->get($secretary, 'full_name'); ?></option>

	<?php endforeach ?>

			</select>
		</div>

<?php endif ?>
<?php if ($this->get('model_ttplayer')): ?>

		<div class="row">
			<h3>Registered players</h3>
			<ul>

	<?php foreach ($this->get('model_ttplayer') as $player): ?>

				<li><a href="<?php echo $this->url(); ?>admin/league/player/?edit=<?php echo $this->get($player, 'id'); ?>" title="Edit Player <?php echo $this->get($player, 'full_name'); ?>"><?php echo $this->get($player, 'full_name'); ?></a></li>
				 
	<?php endforeach ?>

			</ul>
		</div>

<?php endif ?>

		<input name="form_<?php echo ($this->get('model_ttteam') ? 'update' : 'create'); ?>" type="hidden" value="true">
		<a href="#" class="submit button"><?php echo ($this->get('model_ttteam') ? 'Save' : 'Create'); ?></a>
		<input type="submit">
	</form>
</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>