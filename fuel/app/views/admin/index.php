<?php echo Html::anchor('admin/add_message', 'Add New Message', array('class' => 'btn btn-success')); ?>
 | <?php echo Html::anchor('admin/view_user_logs', 'View User Logs', array('class' => 'btn btn-success')); ?>
 | <?php echo Html::anchor('admin/view_message_logs', 'View Message Logs', array('class' => 'btn btn-success')); ?>
 | <?php echo Html::anchor('main/sign_out', 'Sign Out', array('class' => 'btn btn-success')); ?>
<hr>

<h2>Listing <span class='muted'>Users</span></h2>
<?php if ($users): ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user->username; ?></td>
                <td><?php echo $user->password; ?></td>
                <td>
                    <?php if ($user->is_admin == 0): ?>
                    <?php echo Html::anchor('admin/delete_user/'.$user->id, '<i class="icon-trash" title="Delete"></i>', array('onclick' => "return confirm('Are you sure?')")); ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No Users.</p>
<?php endif; ?><p>
<hr>

<h2>Listing <span class='muted'>Messages</span></h2>
<br>
<?php if ($messages): ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Time</th>
            <th>User</th>
            <th>Title</th>
            <th>Message</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?php echo $message->time; ?></td>
                <td><?php echo $message->username; ?></td>
                <td><?php echo $message->title; ?></td>
                <td><?php echo $message->message; ?></td>
                <td>
                    <?php echo Html::anchor('admin/view_message/'.$message->id, '<i class="icon-eye-open" title="View"></i>'); ?> |
                    <?php echo Html::anchor('admin/edit_message/'.$message->id, '<i class="icon-wrench" title="Edit"></i>'); ?> |
                    <?php echo Html::anchor('admin/delete_message/'.$message->id, '<i class="icon-trash" title="Delete"></i>', array('onclick' => "return confirm('Are you sure?')")); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No Messages.</p>
<?php endif; ?>
<hr>
