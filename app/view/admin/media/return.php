<?php if ($modelMedia): ?>

<div class="media-feedback-messages">
	
	<?php foreach ($modelMedia['success'] as $report): ?>
		
	<div class="message success">
		<p><strong><?php echo $report['name'] ?></strong> <?php echo $report['message'] ?></p>
	</div>

	<?php endforeach ?>
	<?php foreach ($modelMedia['error'] as $report): ?>
			
	<div class="message error">
		<p><strong><?php echo $report['name'] ?></strong> <?php echo $report['message'] ?></p>
	</div>

	<?php endforeach ?>

</div>
	
<?php endif ?>
