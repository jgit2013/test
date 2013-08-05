<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label('Id', 'id', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('id', Input::post('id', isset($message) ? $message->id : ''), array('class' => 'span4', 'placeholder'=>'Id')); ?>

			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Title', 'title', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('title', Input::post('title', isset($message) ? $message->title : ''), array('class' => 'span4', 'placeholder'=>'Title')); ?>

			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Message', 'message', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::textarea('message', Input::post('message', isset($message) ? $message->message : ''), array('class' => 'span8', 'rows' => 8, 'placeholder'=>'Message')); ?>

			</div>
		</div>
		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
				<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>			</div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>