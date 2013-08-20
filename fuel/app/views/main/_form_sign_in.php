<?php echo Form::open(array("class"=>"form-horizontal")); ?>
    <fieldset>
        <div class="control-group">
            <?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::input('username', Input::post('username', isset($sign_in) ? $sign_in->username : ''), array('class' => 'span4', 'placeholder'=>'Username')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Password', 'password', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php echo Form::password('password', Input::post('password', isset($sign_in) ? $sign_in->password : ''), array('class' => 'span4', 'placeholder'=>'Password')); ?>
            </div>
        </div>
        
        <div class="control-group">
            <?php echo Form::label('Captcha', 'captcha', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php if ($captcha_driver == 'simplecaptcha'): ?>
                    <?php echo Captcha::forge('simplecaptcha')->html(); ?>
                <?php elseif ($captcha_driver == 'recaptcha'): ?>
                    <?php echo Captcha::forge('recaptcha')->html(); ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class='control-label'>&nbsp;</label>
            
            <div class='controls'>
                <?php echo Form::submit('submit', 'Sign in', array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </fieldset>
<?php echo Form::close(); ?>
