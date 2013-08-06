<?php echo Form::open(array("class"=>"form-horizontal")); ?>
	<fieldset>
		<div class="control-group">
			<?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo Form::label($user->username, 'showusername', array('class' => 'span4', 'placeholder'=>'Username')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Old Password', 'oldpassword', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::password('oldpassword', '', array('class' => 'span4', 'placeholder'=>'Please Type Your Old Password')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('New Password', 'newpassword', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::password('newpassword', '', array('class' => 'span4', 'placeholder'=>'Please Type Your New Password')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
				<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>
			</div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>
