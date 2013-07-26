<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content player all">
	<h1>All registered players</h1>

<?php if ($this->get('model_ttplayer')) : ?>

	<table class="main" width="100%" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="full-name text-left">Name</th>
				<th class="rank text-center">Rank</th>
				<th class="team text-left">Team</th>
				<th class="division text-left">Division</th>
			</tr>
		</thead>
		<tbody>
			
	<?php foreach ($this->get('model_ttplayer') as $player): ?>

		<tr>
			<td class="full-name">
				<a href="<?php echo $this->get($player, 'guid'); ?>" title="View player <?php echo $this->get($player, 'full_name'); ?>"><?php echo $this->get($player, 'full_name'); ?></a>
			</td>
			<td class="rank text-center"><?php echo $this->get($player, 'rank'); ?></td>
			<td class="team"><?php echo $this->get($player, 'team_name'); ?></td>
			<td class="division"><?php echo $this->get($player, 'division_name'); ?></td>
		</tr>		

	<?php endforeach ?>

		</tbody>			
	</table>
	
<?php endif ?>	

</div>

<?php require_once($this->pathView() . 'footer.php'); ?>