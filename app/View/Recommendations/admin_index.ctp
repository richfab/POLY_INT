<div class="recommendations index">
	<h2><?php echo __('Recommendations'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('content'); ?></th>
			<th><?php echo $this->Paginator->sort('experience_id'); ?></th>
			<th><?php echo $this->Paginator->sort('recommendationtype_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($recommendations as $recommendation): ?>
	<tr>
		<td><?php echo h($recommendation['Recommendation']['id']); ?>&nbsp;</td>
		<td><?php echo h($recommendation['Recommendation']['content']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($recommendation['Experience']['id'], array('controller' => 'experiences', 'action' => 'view', $recommendation['Experience']['id'])); ?>
		</td>
		<td>
			<?php echo h($recommendation['Recommendationtype']['name']); ?>
		</td>
		<td><?php echo h($recommendation['Recommendation']['created']); ?>&nbsp;</td>
		<td><?php echo h($recommendation['Recommendation']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $recommendation['Recommendation']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $recommendation['Recommendation']['id']), null, __('Are you sure you want to delete # %s?', $recommendation['Recommendation']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Experiences'), array('controller' => 'experiences', 'action' => 'index')); ?> </li>
	</ul>
</div>
