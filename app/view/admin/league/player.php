<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div id="content" class="league player">
	<h1>Players</h1>
	<div class="clearfix text-right row">
		<a class="new button" href="<?php echo $this->url('current_noquery'); ?>new/" title="Add a new player">New</a>
	</div>

<?php if ($this->get('model_ttplayer')) : ?>

	<table class="main" width="100%" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="text-left">Name</th>
				<th class="text-center">Rank</th>
				<th class="text-left">Team</th>
				<th class="text-left">Division</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			
	<?php foreach ($this->get('model_ttplayer') as $player): ?>

		<tr data-id="<?php echo $this->get($player, 'id'); ?>">
			<td>
				<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $this->get($player, 'id'); ?>" title="Edit Player <?php echo $this->get($player, 'full_name'); ?>"><?php echo $this->get($player, 'full_name'); ?></a>
			</td>
			<td class="text-center"><?php echo $this->get($player, 'rank'); ?></td>
			<td><?php echo $this->get($player, 'team_name'); ?></td>
			<td><?php echo $this->get($player, 'division_name'); ?></td>
			<td class="action">
				<a href="<?php echo $this->get($player, 'guid'); ?>" title="View <?php echo $this->get($player, 'full_name'); ?> online">View</a>
				<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $this->get($player, 'id'); ?>" title="Edit <?php echo $this->get($player, 'full_name'); ?>">Edit</a>
				<a href="<?php echo $this->url('current_noquery'); ?>?delete=<?php echo $this->get($player, 'id'); ?>" title="Delete <?php echo $this->get($player, 'full_name'); ?>">Delete</a>
			</td>
		</tr>		

	<?php endforeach ?>

		</tbody>			
	</table>
	
<?php endif; ?>	

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'admin/footer.php'); ?>