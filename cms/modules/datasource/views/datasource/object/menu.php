<script>
	$(function() {
		$('#ds-menu .widget-content > ul').treeview({
			collapsed:	true,
			unique:		true,
			persist:	"location"
		});
	})
</script>
<?php if(!empty($tree)): ?>
<div class="outline">
	<div id="ds-menu" class="widget outline_inner">
		<div class="widget-content widget-nopad">
			<ul class="unstyled" >
				<?php foreach ($tree as $section => $data): ?>
				<li><?php echo __(ucfirst($section)); ?>
					<ul class="unstyled" >
					<?php foreach ($data as $node => $name): ?>
						<li><?php echo HTML::anchor('datasources/objects/' . URL::query(array('node' => $section . '.' . $node), FALSE), $name); ?></li>
					<?php endforeach; ?>
					</ul>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		
	</div>
</div>
<?php endif; ?>