<div class="widget">

<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>
	
	<?php echo Form::hidden('ds_type', $ds_type); ?>
	<?php echo Form::hidden('obj_type', $obj_type); ?>
	
	<div class="widget-header">
		<h4><?php echo __('General Information'); ?></h4>
	</div>
	<div class="widget-content">

		<div class="control-group">
			<label class="control-label" for="object_name"><?php echo __('Object Header'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'name', NULL, array(
					'class' => 'input-xlarge', 'id' => 'object_name'
				) );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="object_description"><?php echo __('Object Description'); ?></label>
			<div class="controls">
				<?php
				echo Form::textarea( 'description', NULL, array(
					'class' => 'input-xlarge', 'id' => 'object_description'
				) );
				?>
			</div>
		</div>
	</div>
	<div class="widget-header">
		<h4><?php echo __('Data'); ?></h4>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label" for="ds_id"><?php echo __('Section'); ?></label>
			<div class="controls">
				<?php
				echo Form::select( 'ds_id', $options, NULL, array(
					'class' => 'input-xlarge', 'id' => 'ds_id'
				) );
				?>
			</div>
		</div>
	</div>
	<div class="widget-footer form-actions">
		<?php echo UI::button( __('Create object'), array(
			'icon' => UI::icon( 'plus'), 'class' => 'btn btn-large'
		)); ?>
	</div>
<?php echo Form::close(); ?>
</div>