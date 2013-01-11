<div class="widget-header">
	<h4><?php echo __('Template'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('caching', 1, $object->caching); ?> <?php echo __('Enable Caching'); ?></label>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="cache_lifetime"><?php echo __('Set Cache update interval (in seconds)'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'cache_lifetime', $object->cache_lifetime, array(
				'class' => 'input-xlarge', 'id' => 'cache_lifetime'
			) );
			?>
		</div>
	</div>
</div>