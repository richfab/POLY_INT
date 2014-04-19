<div class="experiences form">
<?php echo $this->Form->create('Experience'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Experience'); ?></legend>
	<?php
		echo $this->Form->input('dateStart');
		echo $this->Form->input('dateEnd');
		echo $this->Form->input('establishment');
		echo $this->Form->input('description');
		echo $this->Form->input('comment');
		echo $this->Form->input('city_id');
		echo $this->Form->input('motive_id');
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Experiences'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cities'), array('controller' => 'cities', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New City'), array('controller' => 'cities', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Motives'), array('controller' => 'motives', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Motive'), array('controller' => 'motives', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
