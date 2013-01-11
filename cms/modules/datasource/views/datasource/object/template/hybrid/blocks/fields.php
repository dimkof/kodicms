<?php
$fields = DataSource_Data_Hybrid_Field_Factory::get_related_fields($object->ds_id);

$hobjects = Datasource_Object_Manager::get_objects('hybrid', 'hl');
$objects = array('------');
foreach ($hobjects as $id => $obj)
{
	if($id == $object->id) continue;
	$objects[$id] = $obj['name'];
}

?>
<div class="widget-header">
	<h4><?php echo __('Fetched document fields'); ?></h4>
</div>
<div class="widget-content widget-nopad">
	<table id="section-fields" class="table table-striped">
		<colgroup>
			<col width="30px" />
			<col width="100px" />
			<col width="200px" />
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
				<td></td>
			</tr>
			<tr>
				<td class="f">
					<?php echo Form::checkbox('field[]', 'header', TRUE, array(
						'disabled' => 'disabled'
					)); ?>
				</td>
				<td class="sys">header</td>
				<td><?php echo __('Header'); ?></td>
				<td></td>
			</tr>
			
			<?php foreach($fields as $f): ?>
			<tr id="field-<?php echo $f->name; ?>">
				<td class="f">
					<?php
					echo Form::checkbox('field['.$f->id.'][id]', $f->id, in_array($f->id, $object->doc_fields)); ?>

				</td>
				<td class="sys">
					<?php echo substr($f->name, 2); ?>
				</td>
				<td>
					<?php echo HTML::anchor('hybrid/field/edit/' . $f->id, $f->header, array('target' => 'blank') ); ?>
				</td>
				<td>
					<?php if(in_array($f->family, array(
						DataSource_Data_Hybrid_Field::TYPE_ARRAY,
						DataSource_Data_Hybrid_Field::TYPE_DOCUMENT
					)) AND !empty($objects)): ?>
					
					
					<?php 
					$selected = NULL;
					if(isset($object->doc_fetched_objects[$f->id]))
					{
						$selected = $object->doc_fetched_objects[$f->id];
					}
					
					echo Form::select('field['.$f->id.'][fetcher]', $objects, $selected); 
					?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>