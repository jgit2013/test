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
                <?php echo Form::input('title', $found_message->title, array('class' => 'span4', 'placeholder'=>'Title')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Message', 'message', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::textarea('message', $found_message->message, array('class' => 'span8', 'rows' => 8, 'placeholder'=>'Message')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class='control-label'>&nbsp;</label>
            
            <div class='controls'>
                <?php echo Html::anchor('common/view_message/'.$found_message->id, 'View', array('class' => 'btn btn-primary')); ?>
                 | <?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>
                
                <?php if (Session::get('is_admin') == '1'): ?>
                     | <?php echo Html::anchor('admin', 'Back', array('class' => 'btn btn-primary')); ?>
                <?php else: ?>
                     | <?php echo Html::anchor('user', 'Back', array('class' => 'btn btn-primary')); ?>
                <?php endif; ?>
            </div>
        </div>
    </fieldset>
<?php echo Form::close(); ?>
