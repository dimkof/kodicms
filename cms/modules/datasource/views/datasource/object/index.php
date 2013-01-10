<div class="outline">
	<div id="headline" class="widget outline_inner">
		<?php if($cur_node !== NULL): ?>
		<div class="tablenav form-inline widget-header page-actions">
			<?php echo UI::button(__('Create Object'), array(
				'href' => 'datasources/objects/create' . URL::query(array('node' => $cur_node)),
				'icon' => UI::icon( 'plus' )
			)); ?>

			<div class="input-append pull-right">
				<?php echo Form::select('object_actions', array(
					'Actions', 
					'remove' => 'Remove'), NULL, array(
					'id' => 'object-actions', 'class' => 'input-medium no-script'
				)); ?>

				<?php echo UI::button(__('Apply'), array(
					'id' => 'apply-object-action'
				)); ?>
			</div>
		</div>
		<div class="widget-content widget-nopad">
		<?php echo $headline; ?>
		</div>
		<?php else: ?>
		<div class="widget-content">
		<h3><?php echo __('Select section'); ?></h3>
		</div>
		<?php endif; ?>
	</div>
</div>
