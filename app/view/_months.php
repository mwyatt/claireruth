<?php if ($rowMonths): ?>
	
	<ul class="months clearfix">
		<h3 class="months-title">Months:</h3>

	<?php foreach ($rowMonths as $keyMonth => $rowMonth): ?>
		<?php require($this->pathView('_month')) ?>
	<?php endforeach ?>
		
	</ul>

<?php endif ?>
