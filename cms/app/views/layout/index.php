<div class="map widget widget-nopad">
	
	<div class="widget-header">
		<?php echo UI::button(__('Add layout'), array(
			'icon' => UI::icon( 'plus' ), 'href' => 'layout/add',
			'class' => 'popup fancybox.iframe btn'
		)); ?>
	</div>
	
	<div class="widget-content">
		<table class=" table table-striped table-hover" id="LayoutList">
			<colgroup>
				<col />
				<col width="150px" />
				<col width="100px" />
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Layout name'); ?></th>
					<th><?php echo __('Direction'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($layouts as $layout): ?>
				<tr>
					<th class="name">
						<?php echo HTML::anchor('layout/edit/'.$layout->name, HTML::image(ADMIN_RESOURCES . 'images/layout.png') .' '. $layout->name, array('class' => 'popup fancybox.iframe')); ?>
					</th>
					<td class="direction">
						<?php echo UI::label('/layouts/' . $layout->name . EXT); ?>
					</td>
					<td class="actions">
						<?php echo UI::button(NULL, array(
							'icon' => UI::icon( 'remove' ), 'href' => 'layout/delete/'. $layout->name,
							'class' => 'btn btn-mini btn-confirm'
						)); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div><!--/#layoutMap-->