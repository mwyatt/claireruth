<?php if ($feedback): ?>
	<?php if (array_key_exists('message', $feedback)): ?>
		
	<div class="message<?php echo (array_key_exists('positivity', $feedback) ? ' is-' . $feedback['positivity'] : '') ?> js-dismiss clearfix" title="Dismiss me">
		<p><?php echo $feedback['message'] ?></p>
	</div>

	<?php endif ?>
<?php endif ?>
