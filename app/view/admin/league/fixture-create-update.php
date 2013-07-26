<?php require_once($this->pathView() . 'admin/header.php'); ?>
<?php $tabIndex = 1; ?>	

<div class="content fixtures clearfix<?php echo ($this->get('fixture') ? ' update' : ' fulfill') ?>">
	<h2><?php echo ($this->get('fixture') ? 'Update ' : '') ?>Scorecard</h2>
	<form class="main" method="post">

<?php if ($this->get('model_ttdivision')): ?>

		<div class="row division<?php echo ($this->get('fixture') ? ' hidden' : '') ?>">
			<select id="division_id" name="division_id">
				<option value="0"></option>
				 
	<?php foreach ($this->get('model_ttdivision') as $division): ?>
		
				<option value="<?php echo $this->get($division, 'id'); ?>" <?php echo ($this->get($division, 'id') == $this->get('fixture', 'division_id') ? 'selected' : false); ?>><?php echo $this->get($division, 'name'); ?></option>

	<?php endforeach ?>

			</select>
		</div>

	<?php if ($this->get('fixture')): ?>

		<div class="row">
			<p><?php echo $this->get('fixture', 'division_name') ?></p>
			<p><?php echo $this->get('fixture', 'team_left_name') ?> vs <?php echo $this->get('fixture', 'team_right_name') ?></p>
		</div>

	<?php endif ?>
<?php endif ?>

		<div class="row team<?php echo ($this->get('fixture') ? ' hidden' : '') ?>">
			<select id="team_left" name="team[left]">
				<option value="<?php echo ($this->get('fixture') ? $this->get('fixture', 'team_left_id') : '0') ?>">Home team</option>
			</select>
			<select id="team_right" name="team[right]">
				<option value="<?php echo ($this->get('fixture') ? $this->get('fixture', 'team_right_id') : '0') ?>">Away team</option>
			</select>
		</div>

<?php $row1 = false ?>
<?php $row2 = false ?>
<?php $row3 = false ?>
<?php $row4 = false ?>
<?php if ($this->get('model_admin_ttfixture')): ?>
	<?php $row1 = $this->get('model_admin_ttfixture', 1) ?>
	<?php $row2 = $this->get('model_admin_ttfixture', 2) ?>
	<?php $row3 = $this->get('model_admin_ttfixture', 3) ?>
	<?php $row4 = $this->get('model_admin_ttfixture', 4) ?>
<?php endif ?>

		<div class="row player">

<?php echo (! $this->get('fixture') ? '<span class="play-up button left">Play up</span>' : '') ?>

			<select name="player[left][1]" data-side="left" data-position="1">
				<option value="<?php echo ($this->get($row4, 'player_left_id') ? $this->get($row4, 'player_left_id') : '0') ?>"><?php echo ($this->get($row4, 'player_left_full_name') ? $this->get($row4, 'player_left_full_name') : 'Home player 1') ?></option>
			</select>
			<select name="player[right][1]" data-side="right" data-position="1">
				<option value="<?php echo ($this->get($row1, 'player_right_id') ? $this->get($row1, 'player_right_id') : '0') ?>"><?php echo ($this->get($row1, 'player_right_full_name') ? $this->get($row1, 'player_right_full_name') : 'Away player 1') ?></option>
			</select>

<?php echo (! $this->get('fixture') ? '<span class="play-up button right">Play up</span>' : '') ?>

		</div>
		<div class="row player">

<?php echo (! $this->get('fixture') ? '<span class="play-up button left">Play up</span>' : '') ?>

			<select name="player[left][2]" data-side="left" data-position="2">
				<option value="<?php echo ($this->get($row2, 'player_left_id') ? $this->get($row2, 'player_left_id') : '0') ?>"><?php echo ($this->get($row2, 'player_left_full_name') ? $this->get($row2, 'player_left_full_name') : 'Home player 2') ?></option>
			</select>
			<select name="player[right][2]" data-side="right" data-position="2">
				<option value="<?php echo ($this->get($row3, 'player_right_id') ? $this->get($row3, 'player_right_id') : '0') ?>"><?php echo ($this->get($row3, 'player_right_full_name') ? $this->get($row3, 'player_right_full_name') : 'Away player 2') ?></option>
			</select>

<?php echo (! $this->get('fixture') ? '<span class="play-up button right">Play up</span>' : '') ?>

		</div>		
		<div class="row player">

<?php echo (! $this->get('fixture') ? '<span class="play-up button left">Play up</span>' : '') ?>

			<select name="player[left][3]" data-side="left" data-position="3">
				<option value="<?php echo ($this->get($row1, 'player_left_id') ? $this->get($row1, 'player_left_id') : '0') ?>"><?php echo ($this->get($row1, 'player_left_full_name') ? $this->get($row1, 'player_left_full_name') : 'Home player 3') ?></option>
			</select>
			<select name="player[right][3]" data-side="right" data-position="3">
				<option value="<?php echo ($this->get($row2, 'player_right_id') ? $this->get($row2, 'player_right_id') : '0') ?>"><?php echo ($this->get($row2, 'player_right_full_name') ? $this->get($row2, 'player_right_full_name') : 'Away player 3') ?></option>
			</select>


<?php echo (! $this->get('fixture') ? '<span class="play-up button right">Play up</span>' : '') ?>

		</div>	

<?php foreach ($this->get('encounter_structure') as $row => $parts) : ?>

		<div class="row score<?php echo ($this->get('model_admin_ttfixture', $row, 'status') == 'exclude' ? ' excluded' : '') ?>">

	<?php if ($parts[0] != 'doubles'): ?>
		
			<div class="exclude">
				<label for="exclude-<?php echo $row ?>">Exclude</label>
				<input id="exclude-<?php echo $row ?>" type="checkbox" name="encounter[<?php echo $row; ?>][exclude]"<?php echo ($this->get('model_admin_ttfixture', $row, 'status') == 'exclude' ? ' checked' : '') ?>>
			</div>

	<?php endif ?>

			<label for="<?php echo 'encounter_' . (($parts[0] != 'doubles') ? $row : $parts[0]) . '_' . 'left'; ?>" class="name<?php echo ' player-' . $parts[0] ?> left text-right"><?php echo $this->get('model_admin_ttfixture', $row, 'player_left_full_name') ?><?php echo (($parts[0] !== 'doubles') ? '' : ucfirst($parts[0])); ?></label>
			<input id="<?php echo 'encounter_' . (($parts[0] != 'doubles') ? $row : $parts[0]) . '_' . 'left'; ?>" name="encounter[<?php echo $row; ?>][left]" type="text" size="1" maxlength="1" tabindex="<?php echo $tabIndex ++; ?>" value="<?php echo $this->get('model_admin_ttfixture', $row, 'player_left_score') ?>">
			<input id="<?php echo 'encounter_' . (($parts[1] != 'doubles') ? $row : $parts[1]) . '_' . 'right'; ?>" name="encounter[<?php echo $row; ?>][right]" type="text" size="1" maxlength="1" tabindex="<?php echo $tabIndex ++; ?>" value="<?php echo $this->get('model_admin_ttfixture', $row, 'player_right_score') ?>">
			<label for="<?php echo 'encounter_' . (($parts[1] != 'doubles') ? $row : $parts[1]) . '_' . 'right'; ?>" class="name<?php echo ' player-' . $parts[1] ?> right text-left"><?php echo $this->get('model_admin_ttfixture', $row, 'player_right_full_name') ?><?php echo (($parts[1] !== 'doubles') ? '' : ucfirst($parts[1])); ?></label>
		</div>

<?php endforeach; ?>

		<div class="row total">
			<span class="left"><?php echo ($this->get('fixture', 'team_left_score')) ?></span>
			<span class="right"><?php echo ($this->get('fixture', 'team_right_score')) ?></span>
		</div>
		<div class="row">
			<a href="#" class="submit button"><?php echo ($this->get('fixture') ? 'Update' : 'Fulfill') ?> Fixture</a>
		</div>
		<input name="form_<?php echo ($this->get('fixture') ? 'update' : 'fulfill') ?>" type="hidden">
		<input type="submit">
	</form>
</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>