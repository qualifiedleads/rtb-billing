<table class="table table-bordered">
	<tr>
		<th>BlueKai ID</th><th>Appnexus ID</th><th>Path</th><th>Size</th><th>Description</th><th>CPM</th>
	</tr>
<?php foreach($rows as $row):?>
	<tr>
		<td><?php echo $row->bk_id;?></td>
		<td><?php echo $row->apn_id;?></td>
		<td>
			<?php
			$paths = explode('>', $row->path);
			foreach($paths as $path):
			?>
			<span class="badge" title="<?php echo $path;?>"><span><?php echo $path;?></span><div class="point"></div></span>
			<?php endforeach;?>
		</td>
		<td><?php echo number_format($row->size);?></td>
		<td><?php echo $row->description;?></td>
		<td><?php echo $row->cpm;?></td>
	</tr>
<?php endforeach;?>
</table>