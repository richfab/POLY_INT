<div class="experiences view">
<h2><?php echo __('Experience'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('DateStart'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['dateStart']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('DateEnd'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['dateEnd']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Establishment'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['establishment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['comment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Notify'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['notify']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($experience['Experience']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('City'); ?></dt>
		<dd>
			<?php echo $this->Html->link($experience['City']['name'], array('controller' => 'cities', 'action' => 'view', $experience['City']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Motive'); ?></dt>
		<dd>
			<?php echo $this->Html->link($experience['Motive']['name'], array('controller' => 'motives', 'action' => 'view', $experience['Motive']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($experience['User']['email'], array('controller' => 'users', 'action' => 'view', $experience['User']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Experience'), array('action' => 'edit', $experience['Experience']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Experience'), array('action' => 'delete', $experience['Experience']['id']), null, __('Are you sure you want to delete # %s?', $experience['Experience']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Experiences'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cities'), array('controller' => 'cities', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New City'), array('controller' => 'cities', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Motives'), array('controller' => 'motives', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Motive'), array('controller' => 'motives', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
