<script id="plugin-item" type="text/template">
	<td class="plugin-name">
		<h5>
			<% if (status  && settings) { %>
			<a href="/backend/<%= id %>/settings" class="btn">
				<i class="icon-cog"></i> <%= title %>
			</a>
			<% } else { %>
				<%= title %>
			<% } %>
		</h5>

		<p class="muted"><%= description %></p>
	</td>
	<td class="plugin-version"><%= version %></td>
	<td class="plugin-status">
		<?php echo UI::button(NULL, array(
			'class' => 'change-status btn btn-mini',
		)); ?>
	</td>
</script>

<div class="outline">
	<div id="pluginsMap" class="widget widget-nopad outline_inner">
		<div class="widget-header"></div>
		<div class="widget-content">
			<table class="table table-striped table-hover" id="PluginsList">
				<colgroup>
					<col />
					<col width="80px" />
					<col width="100px" />
				</colgroup>
				<thead>
					<tr>
						<th><?php echo __('Plugin name'); ?></th>
						<th><?php echo __('Version'); ?></th>
						<th><?php echo __('Actions'); ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>