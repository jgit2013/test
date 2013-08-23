<?php echo render('message/_form_view_message'); ?>
<hr>
<h2>Listing <span class='muted'>Comments</span></h2>
<br>
<?php if (isset($found_message_comments)): ?>
    <?php foreach ($found_message_comments as $found_message_comment): ?>
        <?php echo Form::open(array("class"=>"form-horizontal")); ?>
            <fieldset>
                <div class="control-group">
                    <?php echo Form::label('Time', 'time', array('class'=>'control-label')); ?>
                    
                    <div class="controls">
                        <?php echo Form::label($found_message_comment->time, 'show_time', array('class' => 'span4')); ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
                    
                    <div class="controls">
                        <?php echo Form::label($found_message_comment->username, 'show_username', array('class' => 'span4')); ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <?php echo Form::label('Comment', 'comment', array('class'=>'control-label')); ?>
                    
                    <div class="controls">
                        <?php echo Form::label($found_message_comment->comment, 'show_comment', array('class' => 'span4')); ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class='control-label'>&nbsp;</label>
                    
                    <div class='controls'>
                        <?php if ($found_message_comment->username == Session::get('username')): ?>
                            <?php echo Html::anchor('message/edit_comment/'.$found_message_comment->id, 'Edit', array('class' => 'btn btn-primary')); ?> | 
                            <?php echo Html::anchor('message/delete_comment/'.$found_message_comment->id, 'Delete', array('class' => 'btn btn-primary', 'onclick' => "return confirm('Are you sure?')")); ?> | 
                        <?php endif; ?>
                        
                        <?php echo Html::anchor('message', 'Back', array('class' => 'btn btn-primary')); ?>
                    </div>
                </div>
            </fieldset>
        <?php echo Form::close(); ?>
        
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>No Comments.</p>
<?php endif; ?>
