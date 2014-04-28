<div class="medium js-medium-item" data-id="<?php echo ($medium->id ? $medium->id : '') ?>">

	
<!-- 	<a href="<?php echo $medium->thumb_760 ?>" target="_blank" class="medium-thumb js-lightbox-gallery group-1">
		<img src="<?php echo $medium->thumb_150 ?>" alt="<?php echo ($medium->title ? $medium->title : '') ?>">
	</a>
 -->

	<p class="medium-title"><?php echo $medium->title ?></p>
	<span class="medium-date-published"><?php echo $medium->time_published ?></span>
	<span class="medium-author"><?php echo $medium->user_full_name ?></span>
	<span class="medium-delete js-medium-delete">&times;</span>
	<span class="medium-tick"></span>
</div>
