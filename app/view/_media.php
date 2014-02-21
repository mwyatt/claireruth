<?php if ($media): ?>
	
	<div class="media">

	<?php foreach ($media as $medium): ?>
		<?php require($this->pathView('_medium')) ?>
	<?php endforeach ?>
		
	</div>

<?php endif ?>
