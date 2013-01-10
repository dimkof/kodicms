<table class="table table-striped">
	<colgroup>
		<col width="30px" />
		<?php foreach ($fields as $name => $width): ?>
		<col <?php if($width !== NULL) echo 'width="'.$width.'"px'; ?>/>
		<?php endforeach; ?>
	</colgroup>
	<thead>
		<tr>
			<th class="row-checkbox" id="cb-all"><?php echo Form::checkbox('doc[]'); ?></th>
			<?php foreach ($fields as $name => $width): ?>
			<th><?php echo __($name); ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data as $id => $row): ?>
		<tr data-id="<?php echo $id; ?>">
			<td class="row-checkbox"><?php echo Form::checkbox('object[]', $id); ?></td>
			<th class="row-header"><?php echo HTML::anchor('datasources/objects/view/' . $id, $row['name']); ?></th>
			<td class="row-description"><?php echo $row['description']; ?></td>
			<td class="row-date"><?php echo $row['date']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>