<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div id="content" class="league team">
	<h1>Teams</h1>
	<div class="clearfix text-right row">
		<a class="new button" href="<?php echo $this->url('current_noquery'); ?>new/" title="Add a new team">New</a>
	</div>

<?php if ($this->get('model_ttteam')) : ?>

	<table class="main" width="100%" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="text-left">Name</th>
				<th class="text-center">Home night</th>
				<th class="text-left">Venue</th>
				<th class="text-left">Division</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			
	<?php foreach ($this->get('model_ttteam') as $team): ?>

		<tr data-id="<?php echo $this->get($team, 'id'); ?>">
			<td>
				<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $this->get($team, 'id'); ?>" title="Edit team <?php echo $this->get($team, 'name'); ?>"><?php echo $this->get($team, 'name'); ?></a>
			</td>
			<td><?php echo $this->get($team, 'home_night'); ?></td>
			<td><?php echo $this->get($team, 'venue_name'); ?></td>
			<td><?php echo $this->get($team, 'division_name'); ?></td>
			<td class="action">
				<a href="<?php echo $this->get($team, 'guid'); ?>" title="View <?php echo $this->get($team, 'name'); ?> online" target="_blank">View</a>
				<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $this->get($team, 'id'); ?>" title="Edit <?php echo $this->get($team, 'name'); ?>">Edit</a>

		<?php if (! $this->get('options', 'season_status')): ?>
			
				<a href="<?php echo $this->url('current_noquery'); ?>?delete=<?php echo $this->get($team, 'id'); ?>" title="Delete <?php echo $this->get($team, 'name'); ?>">Delete</a>

		<?php endif ?>

			</td>
		</tr>		

	<?php endforeach ?>

		</tbody>			
	</table>
	
<?php endif; ?>	

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'admin/footer.php'); ?>