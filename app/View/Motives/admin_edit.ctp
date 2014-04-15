<div class="motives form">
<?php echo $this->Form->create('Motive'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Motive'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Motive.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Motive.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Motives'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Experiences'), array('controller' => 'experiences', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Experience'), array('controller' => 'experiences', 'action' => 'add')); ?> </li>
	</ul>
</div>
