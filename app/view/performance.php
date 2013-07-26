<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content performance">
	<h1>Performance</h1>

<?php if ($this->get('model_ttencounter_part')) : ?>

	<table class="main" width="100%" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="full-name text-left">Name</th>
				<th class="team text-left">Team</th>
				<th class="rank-change text-left">Rank Change</th>
			</tr>
		</thead>
		<tbody>
			
	<?php foreach ($this->get('model_ttencounter_part') as $player): ?>

		<tr>
			<td class="full-name">
				<a href="<?php echo $this->get($player, 'player_guid'); ?>" title="View player <?php echo $this->get($player, 'player_name'); ?>"><?php echo $this->get($player, 'player_name'); ?></a>
			</td>
			<td class="team">
				<a href="<?php echo $this->get($player, 'team_guid'); ?>" title="View player <?php echo $this->get($player, 'team_name'); ?>"><?php echo $this->get($player, 'team_name'); ?></a>
			</td>
			<td class="rank-change"><?php echo ($player['player_rank_change'] > 0 ? '+' : '') . $player['player_rank_change'] ?></td>
		</tr>		

	<?php endforeach ?>

		</tbody>			
	</table>
	
<?php else: ?>
	<?php require_once($this->pathView() . 'nothing-yet-fixture.php'); ?>
<?php endif; ?>	

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>