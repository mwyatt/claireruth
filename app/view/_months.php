<?php if ($rowMonths): ?>
	
	<ul class="months">

	<?php foreach ($rowMonths as $keyMonth => $rowMonth): ?>
		<?php require($this->pathView('_month')); ?>
	<?php endforeach ?>
		
	</ul>

<?php endif ?>
