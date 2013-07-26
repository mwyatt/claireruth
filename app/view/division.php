<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content division overview" data-division-id="<?php echo $this->get('division', 'id') ?>">
	<h1><?php echo ucfirst($this->get('division', 'name')) ?> division overview</h1>
	<div class="total-blocks clearfix">
		<div class="block">
			<h6><?php echo $this->get('total_players') ?></h6>
			<p>Players</p>
		</div>
		<div class="block">
			<h6><?php echo $this->get('total_teams') ?></h6>
			<p>Teams</p>
		</div>

<?php if ($this->get('total_fixtures')): ?>

		<div class="block">
			<h6><?php echo $this->get('total_fixtures') ?></h6>
			<p>Fixtures</p>
		</div>
		
<?php endif ?>

	</div>

<?php if ($this->get('player') || $this->get('team')): ?>
	<?php if ($this->get('player')) : ?>
	
	<div class="row clearfix">
		<h2>Top 3 players</h2>
		<table class="main" width="100%" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th class="full-name text-left">Name</th>
					<th class="average">Average</th>
				</tr>
			</thead>
			<tbody>
			
		<?php foreach ($this->get('player') as $player): ?>

			<tr>
				<td class="full-name">
					<a href="<?php echo $this->get($player, 'guid'); ?>" title="View player <?php echo $this->get($player, 'full_name'); ?>"><?php echo $this->get($player, 'full_name'); ?></a>
				</td>
				<td class="average text-right"><?php echo $this->get($player, 'average'); ?>&#37;</td>
			</tr>		

		<?php endforeach ?>

			</tbody>			
		</table>
		<div class="text-right">
			<a href="merit/" class="button">Merit Table</a>
		</div>
	</div>
	
	<?php endif; ?>	

	<?php if ($this->get('team')) : ?>
	
	<div class="row clearfix">
		<h2>Top 3 teams</h2>
		<table class="main" width="100%" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th class="name text-left">Name</th>
					<th class="points">Points</th>
				</tr>
			</thead>
			<tbody>
			
		<?php foreach ($this->get('team') as $team): ?>

			<tr>
				<td class="name">
					<a href="<?php echo $this->get($team, 'guid'); ?>" title="View team <?php echo $this->get($team, 'name'); ?>"><?php echo $this->get($team, 'name'); ?></a>
				</td>
				<td class="points text-right"><?php echo $this->get($team, 'points'); ?></td>
			</tr>		

		<?php endforeach ?>

			</tbody>			
		</table>
		<a href="league/" class="button right">League Table</a>
	</div>
	
	<?php endif; ?>
<?php else: ?>
	<?php require_once($this->pathView() . 'nothing-yet-fixture.php'); ?>
<?php endif ?>

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>
