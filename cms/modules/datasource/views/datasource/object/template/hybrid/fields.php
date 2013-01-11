<?php
$fields = DataSource_Data_Hybrid_Field_Factory::get_related_fields($object->ds_id);
$doc_fields = array_flip($object->doc_fields);
?>
<div class="widget-header">
	<h4><?php echo __('Fetched document fields'); ?></h4>
</div>
<div class="widget-content widget-nopad">
	<table id="section-fields" class="table table-striped">
		<colgroup>
			<col width="30px" />
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<td class="f">
					<?php echo Form::checkbox('field[]', 'id', TRUE, array(
						'disabled' => 'disabled'
					)); ?>
				</td>
				<td class="sys">ID</td>
				<td>ID</td>
			</tr>
			<tr>
				<td class="f">
					<?php echo Form::checkbox('field[]', 'header', TRUE, array(
						'disabled' => 'disabled'
					)); ?>
				</td>
				<td class="sys">header</td>
				<td><?php echo __('Header'); ?></td>
			</tr>
			
			<?php foreach($fields as $f): ?>
			<tr id="field-<?php echo $f->name; ?>">
				<td class="f">
					<?php
					echo Form::checkbox('field['.$f->id.']', $f->id, isset($doc_fields[$f->id])); ?>

				</td>
				<td class="sys">
					<?php echo substr($f->name, 2); ?>
				</td>
				<td>
					<?php echo HTML::anchor('hybrid/field/edit/' . $f->id, $f->header, array('target' => 'blank') ); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>