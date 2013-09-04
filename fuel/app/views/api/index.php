<p>API Index</p>
<?php echo Form::open(array("class"=>"form-horizontal")); ?>
    <fieldset>
        <div class="control-group">
			<?php echo Form::label('ID', 'id', array('class'=>'control-label')); ?>
			
			<div class="controls">
				<?php echo Form::input('id', Input::post('id', isset($found) ? $found->id : ''), array('class' => 'span4', 'placeholder'=>'ID')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			
			<div class='controls'>
				<?php echo Form::submit('submit', 'Find ID', array('class' => 'btn btn-primary')); ?>
			</div>
		</div>
    </fieldset>
<?php echo Form::close(); ?>
