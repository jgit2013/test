<?php echo Form::open(array("class"=>"form-horizontal")); ?>
    <fieldset>
        <div class="control-group">
            <?php echo Form::label('Time', 'time', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::label($found_message->time, 'show_time', array('class' => 'span4')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::label($found_message->username, 'show_username', array('class' => 'span4')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Title', 'title', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::label($found_message->title, 'show_title', array('class' => 'span4')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Message', 'message', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::textarea('show_message', $found_message->message, array('class' => 'span8', 'rows' => 8, 'readonly')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class='control-label'>&nbsp;</label>
            
            <div class='controls'>
                <?php if ($found_message->username == Session::get('username')): ?>
                    <?php echo Html::anchor('message/edit/'.$found_message->id, 'Edit', array('class' => 'btn btn-primary')); ?> |
                <?php endif; ?>
                
                <?php echo Html::anchor('message', 'Back', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </fieldset>
<?php echo Form::close(); ?>
