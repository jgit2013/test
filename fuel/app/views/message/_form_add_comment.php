<?php echo Form::open(array("class"=>"form-horizontal")); ?>
    <fieldset>
        <div class="control-group">
            <?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::label(Session::get('username'), 'show_username', array('class' => 'span4')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Comment', 'comment', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::textarea('comment', Input::post('comment', isset($add_comment) ? $add_comment->comment : ''), array('class' => 'span8', 'rows' => 8, 'placeholder'=>'Comment')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class='control-label'>&nbsp;</label>
            
            <div class='controls'>
                <?php echo Form::submit('submit', 'Add', array('class' => 'btn btn-primary')); ?>
                 | <?php echo Html::anchor('message/view_message/'.$message_id, 'Back', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </fieldset>
<?php echo Form::close(); ?>
