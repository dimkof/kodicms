<div class="widget-header">
	<h4><?php echo __('General Information'); ?></h4>
</div>
<div class="widget-content">

	<div class="control-group">
		<label class="control-label" for="object_name"><?php echo __('Object Header'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'name', $object->name, array(
				'class' => 'input-xlarge', 'id' => 'object_name'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="object_description"><?php echo __('Object Description'); ?></label>
		<div class="controls">
			<?php
			echo Form::textarea( 'description', $object->description, array(
				'class' => 'input-xlarge', 'id' => 'object_description', 'rows' => 4
			) );
			?>
		</div>
	</div>
</div>