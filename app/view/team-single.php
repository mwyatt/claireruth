<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content team single" data-id="<?php echo $this->get('info', 'id'); ?>">
	<h1><?php echo $this->get('info', 'name'); ?></h1>
	<div class="row general-information">
		<h2>Information</h2>
		<table class="main" width="100%" cellspacing="0" cellpadding="0">
			<tr class="home-night">
				<th>Home Night</th>
				<td><?php echo $this->get('info', 'home_night'); ?></td>
			</tr>			
			<tr class="venue">
				<th>Venue</th>
				<td><?php echo $this->get('info', 'venue_name'); ?></td>
			</tr>		

<?php if ($this->get('info', 'secretary_full_name')): ?>
	
			<tr class="secretary">
				<th>Secretary</th>
				<td>
					<a href="<?php echo $this->get('info', 'secretary_guid'); ?>"><?php echo $this->get('info', 'secretary_full_name'); ?></a>
					<?php echo ($this->get('info', 'secretary_phone_landline') ? ' | <strong>Landline</strong> ' . $this->get('info', 'secretary_phone_landline') : '') . ($this->get('info', 'secretary_phone_mobile') ? ' | <strong>Mobile</strong> ' . $this->get('info', 'secretary_phone_mobile') : '') ?>
				</td>
			</tr>		

<?php endif ?>

			<tr class="division">
				<th>Division</th>
				<td><a href="<?php echo $this->get('info', 'division_guid'); ?>"><?php echo $this->get('info', 'division_name'); ?></a></td>
			</tr>		
		</table>
	</div>

<?php if ($row = $this->get('stats')): ?>
	
	<div class="row league-stats">
		<h2>League stats</h2>
		<table class="main" width="100%" cellspacing="0" cellpadding="0">
			<tr class="won">
				<th>Won</th>
				<td><?php echo $row['won'] ?></td>
			</tr>			
			<tr class="draw">
				<th>Draw</th>
				<td><?php echo $row['draw'] ?></td>
			</tr>		
			<tr class="lost">
				<th>Lost</th>
				<td><?php echo $row['lost'] ?></td>
			</tr>		
			<tr class="played">
				<th>Played</th>
				<td><?php echo $row['played'] ?></td>
			</tr>		
			<tr class="points">
				<th>Points</th>
				<td><?php echo $row['points'] ?></td>
			</tr>		
		</table>
		<div class="clearfix row">
			<a href="<?php echo $this->urlHome() . 'division/' . $this->get('division', 'name') . '/league/' ?>" class="button right">League Table</a>
		</div>
	</div>

<?php endif ?>

<?php if ($this->get('model_ttplayer')): ?>

	<div class="row players clearfix">
		<h2>Registered players</h2>
		<table class="main" width="100%" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th class="full-name text-left">Name</th>
					<th class="rank text-center">Rank</th>
					<th class="won text-center">Won</th>
					<th class="played text-center">Played</th>
					<th class="average">Average</th>
				</tr>
			</thead>
			<tbody>
			
	<?php foreach ($this->get('model_ttplayer') as $player): ?>

			<tr>
				<td class="full-name">
					<a href="<?php echo $this->get($player, 'guid'); ?>" title="View player <?php echo $this->get($player, 'full_name'); ?>"><?php echo $this->get($player, 'full_name'); ?></a>
				</td>
				<td class="rank text-center"><?php echo $this->get($player, 'rank'); ?></td>
				<td class="won text-center"><?php echo $this->get($player, 'won'); ?></td>
				<td class="played text-center"><?php echo $this->get($player, 'played'); ?></td>
				<td class="average text-right"><?php echo $this->displayAverage($player['average']); ?></td>
			</tr>		

	<?php endforeach ?>

			</tbody>			
		</table>
		<div class="clearfix row">
			<a href="<?php echo $this->urlHome() . 'division/' . $this->get('division', 'name') . '/merit/' ?>" class="button right">Merit Table</a>
		</div>
	</div>

<?php endif ?>

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>