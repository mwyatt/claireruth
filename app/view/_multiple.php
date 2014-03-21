<?php if ($$config->multiple): ?>
	
	<div class="<?php echo $config->multiple ?> js-<?php echo $config->multiple ?> clearfix">

	<?php foreach ($$config->multiple as $$config->singular): ?>
		<?php require($this->pathView('_' . $config->singular)) ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>


<?php 

$config = new StdClass();
$config->multiple = 'contents';
$config->singular = 'content';

 ?>