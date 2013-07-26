<?php require_once($this->pathView() . 'header.php'); ?>

<div class="content fixture">
	
	<h1><?php echo $ttDivision->get('name') ?> Divsion Fixtures</h1>

	<?php if ($ttFixture->getData()) : ?>	

		<?php while ($ttFixture->nextRow()) : ?>

	<a href="<?php echo $ttFixture->getRow('guid'); ?>" class="fixture">
		<span class="date"><?php echo $ttFixture->getRow('date_fulfilled'); ?></span>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<th><?php echo $ttFixture->getRow('team_left_name'); ?></th>
				<td><?php echo $ttFixture->getRow('left_score'); ?></td>
			</tr>
			<tr>
				<th><?php echo $ttFixture->getRow('team_right_name'); ?></th>
				<td><?php echo $ttFixture->getRow('right_score'); ?></td>
			</tr>
		</table>
	</a>

		<?php endwhile; ?>
	
	<?php endif; ?>	

</div> <!-- styling aid -->

<?php require_once($this->pathView() . 'footer.php'); ?>