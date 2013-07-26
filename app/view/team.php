<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content team all">
	<h1>All registered teams</h1>

<?php if ($this->get('model_ttteam')) : ?>

	<table class="main" width="100%" cellspacing="0" cellpadding="0">

		<tr>
			<th class="name">Name</th>
			<th class="player">Players</th>
			<th class="home-night">Home Night</th>
			<th class="venue">Venue</th>
			<th class="division">Division</th>
		</tr>

	<?php foreach ($this->get('model_ttteam') as $team): ?>

		<tr>
			<td class="name">
				<a href="<?php echo $this->get($team, 'guid'); ?>" title="View Team <?php echo $this->get($team, 'name'); ?>"><?php echo $this->get($team, 'name'); ?></a>
			</td>
			<td class="player"><?php echo $this->get($team, 'player_count'); ?></td>
			<td class="home-night"><?php echo $this->get($team, 'home_night'); ?></td>
			<td class="venue"><?php echo $this->get($team, 'venue_name'); ?></td>
			<td class="division"><?php echo $this->get($team, 'division_name'); ?></td>
		</tr>		

	<?php endforeach; ?>

	</table>
	
<?php endif; ?>	

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'footer.php'); ?>