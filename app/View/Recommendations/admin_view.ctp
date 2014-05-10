<div class="recommendations view">
<h2><?php echo __('Recommendation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($recommendation['Recommendation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo h($recommendation['Recommendation']['content']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($recommendation['Recommendation']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($recommendation['Recommendation']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Experience'); ?></dt>
		<dd>
			<?php echo $this->Html->link($recommendation['Experience']['id'], array('controller' => 'experiences', 'action' => 'view', $recommendation['Experience']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($recommendation['Recommendationtype']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete Recommendation'), array('action' => 'delete', $recommendation['Recommendation']['id']), null, __('Are you sure you want to delete # %s?', $recommendation['Recommendation']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Recommendations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Experiences'), array('controller' => 'experiences', 'action' => 'index')); ?> </li>
		
		
	</ul>
</div>
