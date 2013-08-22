<?php echo Form::open(array("class"=>"form-horizontal")); ?>
    <fieldset>
        <div class="control-group">
            <?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::label(Session::get('username'), 'show_username', array('class' => 'span4')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Title', 'title', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::input('title', Input::post('title', isset($add_message) ? $add_message->title : ''), array('class' => 'span4', 'placeholder'=>'Title')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Message', 'message', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::textarea('message', Input::post('message', isset($add_message) ? $add_message->message : ''), array('class' => 'span8', 'rows' => 8, 'placeholder'=>'Message')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class='control-label'>&nbsp;</label>
            
            <div class='controls'>
                <?php echo Form::submit('submit', 'Add', array('class' => 'btn btn-primary')); ?>
                | <?php echo Html::anchor('admin', 'Back', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </fieldset>
<?php echo Form::close(); ?>
