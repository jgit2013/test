<?php echo Form::open(array("class"=>"form-horizontal")); ?>
	<fieldset>
		<div class="control-group">
			<?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('username', Input::post('username', isset($login) ? $login->username : ''), array('class' => 'span4', 'placeholder'=>'Username')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Password', 'password', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::password('password', Input::post('password', isset($login) ? $login->password : ''), array('class' => 'span4', 'placeholder'=>'Password')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Is Admin', 'is_admin', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::password('is_admin', Input::post('is_admin', isset($login) ? $login->is_admin : ''), array('class' => 'span4', 'placeholder'=>'Is Admin')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
				<?php echo Form::submit('submit', 'Create', array('class' => 'btn btn-primary')); ?>
			</div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
