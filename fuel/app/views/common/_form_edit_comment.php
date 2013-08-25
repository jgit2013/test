<?php echo Form::open(array("class"=>"form-horizontal")); ?>
    <fieldset>
        <div class="control-group">
            <?php echo Form::label('Time', 'time', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::label($found_comment->time, 'show_time', array('class' => 'span4')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::label($found_comment->username, 'show_username', array('class' => 'span4')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Comment', 'comment', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::textarea('comment', $found_comment->comment, array('class' => 'span8', 'rows' => 8, 'placeholder'=>'Comment')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class='control-label'>&nbsp;</label>
            
            <div class='controls'>
                <?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>
                 | <?php echo Html::anchor('common/view_message/'.$found_comment->message_id, 'Back', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </fieldset>
<?php echo Form::close(); ?>
