<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div class="content fixtures update clearfix">
	<h2>Update Scorecard</h2>
	<form method="post">
		<div class="row">
			<p><?php echo $this->get('fixture', 'division_name') ?></p>
			<p><?php echo $this->get('fixture', 'team_left_name') ?> vs <?php echo $this->get('fixture', 'team_right_name') ?></p>
		</div>

<?php $tabIndex = 1; ?>
<?php foreach ($this->get('model_admin_ttfixture') as $row => $encounter): ?>
	
		<div class="row">
			<label for="disable-<?php echo $row ?>">Excluude from merit</label>
			<input id="disable-<?php echo $row ?>" type="checkbox" name="encounter[<?php echo $row; ?>][exclude]"<?php echo ($this->get($encounter, 'status') == 'exclude' ? ' checked' : '') ?>>
			<label for="encounter_<?php echo $row ?>_left" class="name"><?php echo (($this->get($encounter, 'status') != 'doubles') ? $this->get($encounter, 'player_left_full_name') : ucfirst($this->get($encounter, 'status'))); ?></label>
			<input id="encounter_<?php echo $row ?>_left" name="encounter[<?php echo $row; ?>][left]" type="text" size="1" maxlength="1" value="<?php echo $this->get($encounter, 'player_left_score') ?>" tabindex="<?php echo $tabIndex ++; ?>">
			<input id="encounter_<?php echo $row ?>_right" name="encounter[<?php echo $row; ?>][right]" type="text" size="1" maxlength="1" value="<?php echo $this->get($encounter, 'player_right_score') ?>" tabindex="<?php echo $tabIndex ++; ?>">
			<label for="encounter_<?php echo $row ?>_right" class="name"><?php echo (($this->get($encounter, 'status') != 'doubles') ? $this->get($encounter, 'player_right_full_name') : ucfirst($this->get($encounter, 'status'))); ?></label>
		</div>

<?php endforeach ?>

		<div class="total">
			<p></p>
		</div>
		<input name="form_update" type="hidden" value="true">
		<a href="#" class="submit button">Save</a>
		<input type="submit">
	</form>
</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>