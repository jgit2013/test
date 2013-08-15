<?php echo Form::open(array("class"=>"form-horizontal")); ?>
	<fieldset>
		<div class="control-group">
			<?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('username', Input::post('username', isset($log) ? $log->username : ''), array('class' => 'span4', 'placeholder'=>'Username')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Sign In Time', 'sign_in_time', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('sign_in_time', Input::post('sign_in_time', isset($log) ? $log->sign_in_time : ''), array('class' => 'span4', 'placeholder'=>'Sign In Time')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Sign Out Time', 'sign_out_time', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('sign_out_time', Input::post('sign_out_time', isset($log) ? $log->sign_out_time : ''), array('class' => 'span4', 'placeholder'=>'Sign Out Time')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
				<?php echo Form::submit('submit', 'Find', array('class' => 'btn btn-primary')); ?>
			</div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
