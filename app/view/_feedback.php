<?php if ($feedback): ?>
	<?php if (array_key_exists('message', $feedback)): ?>
		
	<div class="feedback-container<?php echo (array_key_exists('positivity', $feedback) ? ' is-' . $feedback['positivity'] : '') ?> js-dismiss clearfix" title="Dismiss me">
		<p class="feedback-description"><?php echo $feedback['message'] ?></p>
	</div>

	<?php endif ?>
<?php endif ?>
