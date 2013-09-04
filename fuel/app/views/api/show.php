<?php echo Html::anchor('api', 'Back', array('class' => 'btn btn-success')); ?>
<h2>Listing <span class='muted'>Data</span></h2>
<?php if (isset($founds)): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>Username</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
        <?php foreach ($founds as $found): ?>
            <tr>
                <td><?php echo $found->id; ?></td>
                <td><?php echo $found->username; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No found.</p>
<?php endif; ?><p>
<hr>
