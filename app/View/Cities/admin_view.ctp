<div class="cities view">
<h2><?php echo __('City'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($city['City']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($city['City']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lat'); ?></dt>
		<dd>
			<?php echo h($city['City']['lat']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lon'); ?></dt>
		<dd>
			<?php echo h($city['City']['lon']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('ExperienceNumber'); ?></dt>
		<dd>
			<?php echo h($city['City']['experienceNumber']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($city['City']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($city['City']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Country'); ?></dt>
		<dd>
			<?php echo $this->Html->link($city['Country']['name'], array('controller' => 'countries', 'action' => 'view', $city['Country']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit City'), array('action' => 'edit', $city['City']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete City'), array('action' => 'delete', $city['City']['id']), null, __('Are you sure you want to delete # %s?', $city['City']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cities'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New City'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Countries'), array('controller' => 'countries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Country'), array('controller' => 'countries', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Experiences'), array('controller' => 'experiences', 'action' => 'index')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Experiences'); ?></h3>
	<?php if (!empty($city['Experience'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('DateStart'); ?></th>
		<th><?php echo __('DateEnd'); ?></th>
		<th><?php echo __('Establishment'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th><?php echo __('Notify'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('City Id'); ?></th>
		<th><?php echo __('Motive Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($city['Experience'] as $experience): ?>
		<tr>
			<td><?php echo $experience['id']; ?></td>
			<td><?php echo $experience['dateStart']; ?></td>
			<td><?php echo $experience['dateEnd']; ?></td>
			<td><?php echo $experience['establishment']; ?></td>
			<td><?php echo $experience['description']; ?></td>
			<td><?php echo $experience['comment']; ?></td>
			<td><?php echo $experience['notify']; ?></td>
			<td><?php echo $experience['created']; ?></td>
			<td><?php echo $experience['modified']; ?></td>
			<td><?php echo $experience['city_id']; ?></td>
			<td><?php echo $experience['motive_id']; ?></td>
			<td><?php echo $experience['user_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'experiences', 'action' => 'view', $experience['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'experiences', 'action' => 'edit', $experience['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'experiences', 'action' => 'delete', $experience['id']), null, __('Are you sure you want to delete # %s?', $experience['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
