<div class="schools form">
<?php echo $this->Form->create('School'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add School'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('color',array('class' => 'color {required:true}'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Schools'), array('action' => 'index')); ?></li>
	</ul>
</div>
