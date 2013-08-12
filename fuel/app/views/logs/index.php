<h2>Listing <span class='muted'>Logs</span></h2>
<br>
<?php if ($logs): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($logs as $log): ?>		<tr>

			<td>
				<?php echo Html::anchor('logs/view/'.$log->id, '<i class="icon-eye-open" title="View"></i>'); ?> |
				<?php echo Html::anchor('logs/edit/'.$log->id, '<i class="icon-wrench" title="Edit"></i>'); ?> |
				<?php echo Html::anchor('logs/delete/'.$log->id, '<i class="icon-trash" title="Delete"></i>', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Logs.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('logs/create', 'Add new Log', array('class' => 'btn btn-success')); ?>

</p>
