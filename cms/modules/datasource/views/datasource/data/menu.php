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
		<?php foreach ($tree as $section => $data): ?>
		<div class="widget-header">
		<h4><?php echo __(ucfirst($section)); ?></h4>
		</div>
		<div class="widget-content widget-nopad">
			<ul class="unstyled" >
				<?php foreach ($data as $id => $name): ?>
					<?php echo recurse_menu($id, $name, $ds_id); ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>

<?php
	function recurse_menu($id, $data, $ds_id)
	{
		$result = '';
		$selected = ($id == $ds_id) ? 'selected' : '';

		if(  is_array( $data ))
		{
			$result .= '<li class="'.$selected.'">';
			$result .= HTML::anchor('datasources/data' . URL::query(array(
				'ds_id' => $id
			), FALSE), key($data));
			
			
			$result .= '<ul class="unstyled" >';
			foreach ( $data[key($data)] as $id => $name )
			{
				$result .= recurse_menu($id, $name, $ds_id);
			}
			$result .= '</ul>';
			
			$result .= '</li>';
		}
		else
		{
			$result .= '<li class="'.$selected.'">';
			$result .= HTML::anchor('datasources/data' . URL::query(array(
				'ds_id' => $id
			), FALSE), $data);
			$result .= '</li>';
		}
		
		return $result;
	}

?>