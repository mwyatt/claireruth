<li class="row status-<?php echo $post->getRow('status'); ?>" data-id="<?php echo $post->getRow('id'); ?>" data-type="<?php echo $post->getRow('type'); ?>">
		
	<div class="cell tick js-tick"><span></span></div>
	
	<div class="cell title">
		<a href="<?php echo $this->urlCurrent() . '?edit=' . $post->getRow('id'); ?>" title="Edit <?php echo $post->getRow('title'); ?>"><?php echo $post->getRow('title'); ?></a>
	</div>
	
	<div class="cell date"><?php echo date("dS M Y", strtotime($post->getRow('date_published'))); ?></div>
	
	<div class="actions">
	
		<a class="delete js-delete" title="Delete <?php echo $post->getRow('title'); ?>"></a>
		<a class="visibility js-visibility" title="Toggle Visibility"></a>
		<!--<a class="attached js-attached" title="<?php echo $attached; ?>"></a>-->
		<!--<a class="tags js-tags" title="<?php echo $tags; ?>"></a>-->
		<a class="permalink js-permalink" href="<?php echo $post->getRow('guid'); ?>" title="Permalink to <?php echo $post->getRow('title'); ?>"></a>
	
	</div>
	
</li>