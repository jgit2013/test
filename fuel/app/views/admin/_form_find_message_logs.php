<?php echo Form::open(array("class"=>"form-horizontal")); ?>
	<fieldset>
	    <div class="control-group">
			<?php echo Form::label('Time', 'time', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('time', Input::post('time', isset($log) ? $log->time : ''), array('class' => 'span4', 'placeholder'=>'Time')); ?>
			</div>
		</div>
	    
		<div class="control-group">
			<?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('username', Input::post('username', isset($log) ? $log->username : ''), array('class' => 'span4', 'placeholder'=>'Username')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Action', 'action', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('action', Input::post('action', isset($log) ? $log->action : ''), array('class' => 'span4', 'placeholder'=>'Action')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Before Title', 'before_title', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('before_title', Input::post('before_title', isset($log) ? $log->before_title : ''), array('class' => 'span4', 'placeholder'=>'Before Title')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('After Title', 'after_title', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('after_title', Input::post('after_title', isset($log) ? $log->after_title : ''), array('class' => 'span4', 'placeholder'=>'After Title')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Before Message', 'before_message', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('before_message', Input::post('before_message', isset($log) ? $log->before_message : ''), array('class' => 'span4', 'placeholder'=>'Before Message')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('After Message', 'after_message', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('after_message', Input::post('after_message', isset($log) ? $log->after_message : ''), array('class' => 'span4', 'placeholder'=>'After Message')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Is Succeed', 'is_succeed', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('is_succeed', Input::post('is_succeed', isset($log) ? $log->password : ''), array('class' => 'span4', 'placeholder'=>'Is Succeed')); ?>
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
