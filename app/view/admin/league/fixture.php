<?php require_once($this->pathView() . 'admin/header.php'); ?>

<div class="content league fixtures clearfix">
	<h1>All Fixtures</h1>

<?php if ($this->get('model_ttfixture')) : ?>

	<div class="clearfix text-right row">
		<a class="new button" href="<?php echo $this->url('current_noquery'); ?>fulfill/" title="Submit scorecard">Submit scorecard</a>
	</div>
	
	<?php foreach ($this->get('model_ttdivision') as $division): ?>

	<h2 class="clearfix"><?php echo $this->get($division, 'name') ?> division</h2>

		<?php foreach ($this->get('model_ttfixture') as $fixture): ?>
			<?php if ($this->get($fixture, 'division_id') == $this->get($division, 'id')): ?>
				<?php if ($fixture['date_fulfilled']): ?>
					
	<a href="<?php echo $this->url('current_noquery'); ?>?edit=<?php echo $this->get($fixture, 'id'); ?>" class="card clearfix" title="Edit fixture">
		<span class="date-fulfilled"><?php echo date('D jS F Y', $this->get($fixture, 'date_fulfilled')) ?></span>

				<?php else: ?>

	<span class="card clearfix" title="Not yet filled">

				<?php endif ?>

		<div class="team-left"><?php echo $this->get($fixture, 'team_left_name'); ?></div>
		<div class="score-left"><?php echo $this->get($fixture, 'score_left'); ?></div>
		<div class="team-right"><?php echo $this->get($fixture, 'team_right_name'); ?></div>
		<div class="score-right"><?php echo $this->get($fixture, 'score_right'); ?></div>

				<?php if ($fixture['date_fulfilled']): ?>
						
	</a>

				<?php else: ?>
					
	</span>

				<?php endif ?>
			<?php endif ?>
		<?php endforeach ?>

	<div class="clearfix"></div>

	<?php endforeach ?>
<?php else: ?>

	<div class="notice season-status clearfix">
	    <h3>Season Status</h3>
	    <p>This season has not yet started. Please ensure all <a href="<?php echo $this->urlHome() ?>admin/league/team/" title="Team management">teams</a> are in the correct divisions. Once you 'start' the season the fixtures will be generated and you will be unable to move <a href="<?php echo $this->urlHome() ?>admin/league/team/" title="Team management">teams</a> to other divisions.</p>
	    <a href="<?php echo $this->url('current_noquery') ?>?season=start" class="button season-start right">Start season</a>
	</div>

<?php endif ?>

</div>

<?php require_once($this->pathView() . 'admin/footer.php'); ?>