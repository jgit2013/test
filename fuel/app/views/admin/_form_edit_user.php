<?php echo Form::open(array("class"=>"form-horizontal")); ?>
	<fieldset>
		<div class="control-group">
			<?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>
			
			<div class="controls">
				<?php echo Form::label($user->username, 'showusername', array('class' => 'span4', 'placeholder'=>'Username')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo Form::label('Is Admin', 'is_admin', array('class'=>'control-label')); ?>

			<div class="controls">
			    <?php echo Form::input('is_admin', Input::post('is_admin', isset($user) ? $user->is_admin : ''), array('class' => 'span4', 'placeholder'=>'Is Admin')); ?>
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
