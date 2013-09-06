<?php if ($this->get('feedback')): ?>

<div class="errors">

	<?php foreach ($this->get('feedback') as $feedback): ?>

	<div class="message error">
		
		<?php echo $feedback ?>

	</div>
		
	<?php endforeach ?>

</div>

<?php endif ?>
<?php if ($this->get('media')): ?>

<div class="message success">

	<p><?php echo count($this->get('media')) ?> file<?php echo (count($this->get('media')) > 1 ? 's' : '') ?> successfully uploaded. Please browse for these files and click them to attach.</p>

</div>

<?php endif ?>
