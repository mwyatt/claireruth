<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content player single" data-id="<?php echo $this->get('model_ttplayer', 'id'); ?>">
	<h1><?php echo $this->get('model_ttplayer', 'full_name'); ?></h1>
	<div class="general-information">
		<h2>Information</h2>
		<table class="main" width="100%" cellspacing="0" cellpadding="0">
			<tr class="played">
				<th>Team</th>
				<td><a href="<?php echo $this->get('model_ttplayer', 'team_guid'); ?>"><?php echo $this->get('model_ttplayer', 'team_name'); ?></a></td>
			</tr>		
			<tr class="division">
				<th>Division</th>
				<td><a href="<?php echo $this->get('model_ttplayer', 'division_guid'); ?>"><?php echo $this->get('model_ttplayer', 'division_name'); ?></a></td>
			</tr>	

<?php if ($this->get('model_ttplayer', 'etta_license_number')): ?>
			
			<tr class="etta-license-number">
				<th>ETTA license number</th>
				<td><?php echo $this->get('model_ttplayer', 'etta_license_number'); ?></td>
			</tr>	

<?php endif ?>
<?php if ($this->get('model_ttplayer', 'phone_landline')): ?>
			
			<tr class="phone-landline">
				<th>Telephone landline</th>
				<td><?php echo $this->get('model_ttplayer', 'phone_landline'); ?></td>
			</tr>	

<?php endif ?>
<?php if ($this->get('model_ttplayer', 'phone_mobile')): ?>
			
			<tr class="phone-mobile">
				<th>Telephone mobile</th>
				<td><?php echo $this->get('model_ttplayer', 'phone_mobile'); ?></td>
			</tr>	

<?php endif ?>

		</table>
	</div>

<?php if ($this->get('model_ttplayer', 'played')): ?>

	<div class="merit-stats">
		<h2>Merit stats</h2>
		<table class="main" width="100%" cellspacing="0" cellpadding="0">
			<tr class="played">
				<th>Played</th>
				<td><?php echo $this->get('model_ttplayer', 'played'); ?></td>
			</tr>		
			<tr class="won">
				<th>Won</th>
				<td><?php echo $this->get('model_ttplayer', 'won'); ?></td>
			</tr>		
			<tr class="lost">
				<th>Lost</th>
				<td><?php echo $this->get('model_ttplayer', 'lost'); ?></td>
			</tr>		
			<tr class="average">
				<th>Average</th>
				<td><?php echo $this->get('model_ttplayer', 'average'); ?>&#37;</td>
			</tr>		
		</table>
		<div class="clearfix row">
			<a href="<?php echo $this->get('model_ttplayer', 'division_guid') ?>" class="button right">Merit Table</a>
		</div>
	</div>

<?php endif ?>

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>
