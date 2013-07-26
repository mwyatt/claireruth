<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div class="content <?php echo $this->urlSegment(2); ?> <?php echo ($this->get('model_ttplayer') ? 'update' : 'create'); ?>" data-id="<?php echo $this->get('model_ttplayer', 'id'); ?>">
	<a href="<?php echo $this->url('current_noquery') ?>" class="button back">Back</a>
	<h1><?php echo ($this->get('model_ttplayer') ? 'Update ' . ucfirst($this->urlSegment(2)) . ' ' . $this->get('model_ttplayer', 'full_name') : 'Create new ' . ucfirst($this->urlSegment(2))); ?></h1>
	<form class="main" method="post">
		<div class="row">		
			<label class="above" for="form-first-name">First name</label>
			<input id="form-first-name" class="required" type="text" name="first_name" maxlength="75" value="<?php echo $this->get('model_ttplayer', 'first_name'); ?>">
		</div>			
		<div class="row">
			<label class="above" for="form-last-name">Last name</label>
			<input id="form-last-name" class="required" type="text" name="last_name" maxlength="75" value="<?php echo $this->get('model_ttplayer', 'last_name'); ?>">
		</div>
		<div class="row">			
			<label class="above" for="form-rank">Rank</label>
			<input id="form-rank" class="required" type="text" name="rank" maxlength="75" value="<?php echo $this->get('model_ttplayer', 'rank'); ?>">
		</div>			
		<div class="row">			
			<label class="above" for="form-license-number">ETTA license number</label>
			<input id="form-license-number" class="required" type="text" name="etta_license_number" maxlength="75" value="<?php echo $this->get('model_ttplayer', 'etta_license_number'); ?>">
		</div>		
		<div class="row">			
			<label class="above" for="form-landline">Telephone Number (Landline)</label>
			<input id="form-landline" class="required" type="text" name="phone_landline" maxlength="75" value="<?php echo $this->get('model_ttplayer', 'phone_landline'); ?>">
		</div>			
		<div class="row">			
			<label class="above" for="form-mobile">Telephone Number (Mobile)</label>
			<input id="form-mobile" class="required" type="text" name="phone_mobile" maxlength="75" value="<?php echo $this->get('model_ttplayer', 'phone_mobile'); ?>">
		</div>			

<?php if ($this->get('model_ttdivision')): ?>

		<div class="row division">
			<label class="above" for="form-division-id">Division</label>
			<select id="form-division-id" name="division_id">
				<option value="0"></option>
				 
	<?php foreach ($this->get('model_ttdivision') as $division): ?>
		
				<option value="<?php echo $this->get($division, 'id'); ?>" <?php echo ($this->get($division, 'id') == $this->get('model_ttplayer', 'division_id') ? 'selected' : false); ?>><?php echo $this->get($division, 'name'); ?></option>

	<?php endforeach ?>

			</select>
		</div>
	
<?php endif ?>

		<div class="row team">
			<label class="above" for="form-team-id">Team</label>
			<select id="form-team-id" name="team_id">
				<option value="0"></option>

<?php if ($this->get('model_ttteam')): ?>
	<?php foreach ($this->get('model_ttteam') as $team): ?>
		
				<option value="<?php echo $this->get($team, 'id'); ?>" <?php echo ($this->get($team, 'id') == $this->get('model_ttplayer', 'team_id') ? 'selected' : false); ?>><?php echo $this->get($team, 'name'); ?></option>

	<?php endforeach ?>
<?php endif ?>

			</select>
		</div>
		<input name="form_<?php echo ($this->get('model_ttplayer') ? 'update' : 'create'); ?>" type="hidden" value="true">
		<a href="#" class="submit button">Save</a>
		<input type="submit">
	</form>
</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>