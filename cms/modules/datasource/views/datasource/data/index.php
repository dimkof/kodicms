<div class="outline">
	<div id="headline" class="widget outline_inner">
		<div class="tablenav form-inline widget-header page-actions">
			<?php echo UI::button(__('Create Document'), array(
				'href' => 'hybrid/document/create' . URL::query(array('ds_id' => $ds_id)),
				'icon' => UI::icon( 'plus' )
			)); ?>

			<div class="input-append pull-right">
				<?php echo Form::select('doc_actions', array(
					'Actions', 
					'remove' => 'Remove', 
					'publish' => 'Publish', 
					'unpublish' => 'Unpublish'), NULL, array(
					'id' => 'doc-actions', 'class' => 'input-medium no-script'
				)); ?>

				<?php echo UI::button(__('Apply'), array(
					'id' => 'apply-doc-action'
				)); ?>
			</div>
		</div>
		<div class="widget-content widget-nopad">
		<?php echo $headline; ?>
		</div>
	</div>
</div>