<div class="motives view">
<h2><?php echo __('Motive'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($motive['Motive']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($motive['Motive']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($motive['Motive']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($motive['Motive']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Motive'), array('action' => 'edit', $motive['Motive']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Motive'), array('action' => 'delete', $motive['Motive']['id']), null, __('Are you sure you want to delete # %s?', $motive['Motive']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Motives'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Motive'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Experiences'), array('controller' => 'experiences', 'action' => 'index')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Experiences'); ?></h3>
	<?php if (!empty($motive['Experience'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('DateStart'); ?></th>
		<th><?php echo __('DateEnd'); ?></th>
		<th><?php echo __('Establishment'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('City Id'); ?></th>
		<th><?php echo __('Motive Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($motive['Experience'] as $experience): ?>
		<tr>
			<td><?php echo $experience['id']; ?></td>
			<td><?php echo $experience['dateStart']; ?></td>
			<td><?php echo $experience['dateEnd']; ?></td>
			<td><?php echo $experience['establishment']; ?></td>
			<td><?php echo $experience['description']; ?></td>
			<td><?php echo $experience['comment']; ?></td>
			<td><?php echo $experience['created']; ?></td>
			<td><?php echo $experience['modified']; ?></td>
			<td><?php echo $experience['city_id']; ?></td>
			<td><?php echo $experience['motive_id']; ?></td>
			<td><?php echo $experience['user_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'experiences', 'action' => 'view', $experience['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'experiences', 'action' => 'delete', $experience['id']), null, __('Are you sure you want to delete # %s?', $experience['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
