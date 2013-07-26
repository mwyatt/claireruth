<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content division merit">
	<a href="<?php echo $this->url('back') ?>" class="button right">Back to overview</a>
	<h1><?php echo ucfirst($this->get('division', 'name')) ?> division merit</h1>

<?php if ($this->get('model_ttplayer')) : ?>

	<table class="main" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<th class="full_name">Name</th>
			<th class="team">Team</th>
			<th class="rank">Rank</th>
			<th class="won">Won</th>
			<th class="played">Played</th>
			<th class="average">Average</th>
		</tr>

	<?php foreach ($this->get('model_ttplayer') as $player): ?>

		<tr>
			<td class="full_name">
				<a href="<?php echo $this->get($player, 'guid'); ?>" title="View player <?php echo $this->get($player, 'full_name'); ?>"><?php echo $this->get($player, 'full_name'); ?></a>
			</td>
			<td class="team">
				<a href="<?php echo $this->get($player, 'team_guid'); ?>" title="View team <?php echo $this->get($player, 'team_name'); ?>"><?php echo $this->get($player, 'team_name'); ?></a>
			</td>
			<td class="rank"><?php echo $this->get($player, 'rank'); ?></td>
			<td class="won"><?php echo $this->get($player, 'won'); ?></td>
			<td class="played"><?php echo $this->get($player, 'played'); ?></td>
			<td class="average"><?php echo $this->get($player, 'average'); ?>&#37;</td>
		</tr>		

	<?php endforeach ?>

	</table>
	
<?php else: ?>
	<?php require_once($this->pathView() . 'nothing-yet-fixture.php'); ?>
<?php endif; ?>	

	<p><a href="<?php echo $this->urlHome() ?>player/">Can't find the player your looking for?</a></p>
</div>

<?php require_once($this->pathView() . 'footer.php'); ?>