<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content fixture single">
	<h1><a href="<?php echo $this->get('fixture_info', 'team_left_guid') ?>"><?php echo $this->get('fixture_info', 'team_left_name') ?></a> <span class="vs">vs</span> <a href="<?php echo $this->get('fixture_info', 'team_right_guid') ?>"><?php echo $this->get('fixture_info', 'team_right_name') ?></a></h1>
	<p class="date">Fulfilled on <?php echo date('D jS F Y', $this->get('fixture_info', 'date_fulfilled')); ?></p>

<?php if ($this->get('model_ttfixture')) : ?>

		<table class="main" width="100%" cellspacing="0" cellpadding="0">

	<?php foreach ($this->get('model_ttfixture') as $fixture): ?>
		
			<tr<?php echo ($this->get($fixture, 'status') ? ' class="' . $this->get($fixture, 'status') . '"' : ''); ?>>
				<td class="player text-right">

		<?php if ($this->get($fixture, 'status') == 'doubles') : ?>

					Doubles

		<?php else : ?>
			<?php if ($this->get($fixture, 'player_left_id')): ?>
				
					<a href="<?php echo $this->get($fixture, 'player_left_guid'); ?>"><?php echo $this->get($fixture, 'player_left_full_name'); ?></a>

			<?php else : ?>

					Absent player

			<?php endif ?>
		<?php endif; ?>	

				</td>
				<td class="score"><?php echo $this->get($fixture, 'player_left_score'); ?></td>
				<td class="score"><?php echo $this->get($fixture, 'player_right_score'); ?></td>
				<td class="player text-left">

		<?php if ($this->get($fixture, 'status') == 'doubles') : ?>

					Doubles

		<?php else : ?>
			<?php if ($this->get($fixture, 'player_right_id')): ?>
				
					<a href="<?php echo $this->get($fixture, 'player_right_guid'); ?>"><?php echo $this->get($fixture, 'player_right_full_name'); ?></a>

			<?php else : ?>

					Absent player

			<?php endif ?>
		<?php endif; ?>	

				</td>				
			</tr>

	<?php endforeach ?>

			<tr class="total">
				<th>Total</th>
				<td><?php echo $this->get('fixture_info', 'team_left_score'); ?></td>
				<td><?php echo $this->get('fixture_info', 'team_right_score'); ?></td>
				<td></td>
			</tr>
		</table>
	
<?php endif; ?>	

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'footer.php'); ?>