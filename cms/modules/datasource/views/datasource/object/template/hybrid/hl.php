<div class="widget">

<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>
	
	<?php echo $general; ?>
	
	<?php echo $template; ?>
	
	<?php echo View::factory('datasource/object/template/hybrid/blocks/section', array(
		'object' => $object,
		'options' => $options
	)); ?>

	<div class="widget-header">
		<h4><?php echo __('Properties'); ?></h4>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label" for="header"><?php echo __('Header'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'header', $object->header, array(
					'class' => 'input-xlarge', 'id' => 'header'
				) );
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="list_offset"><?php echo __('Number of documents to omit'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'list_offset', $object->list_offset, array(
					'class' => 'input-small', 'id' => 'list_offset'
				) );
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="list_size"><?php echo __('Number of documents per page'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'list_size', $object->list_size, array(
					'class' => 'input-small', 'id' => 'list_size'
				) );
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="doc_uri"><?php echo __('Document page (URI)'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'doc_uri', $object->doc_uri, array(
					'class' => 'input-xlarge', 'id' => 'doc_uri'
				) );
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="doc_id"><?php echo __('Identificator field'); ?></label>
			<div class="controls">
				<?php
				echo Form::textarea( 'doc_id', $object->doc_id, array(
					'class' => 'input-xlarge', 'id' => 'doc_id', 'rows' => 4
				) );
				?>
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $object->throw_404); ?> <?php echo __('Do not generate error 404 when page has no content'); ?></label>
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $object->only_published); ?> <?php echo __('Show only published documents'); ?></label>
			</div>
		</div>
	</div>
	
	<?php echo View::factory('datasource/object/template/hybrid/blocks/fields', array(
		'object' => $object
	)); ?>
	
	<?php echo View::factory('datasource/object/template/hybrid/blocks/sorting', array(
		'object' => $object
	)); ?>

	<div class="widget-footer form-actions">
		<?php echo UI::button( __('Save object'), array(
			'icon' => UI::icon( 'ok'), 'class' => 'btn btn-large'
		)); ?>
	</div>
	
<?php echo Form::close(); ?>
</div>