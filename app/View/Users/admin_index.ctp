<ul class="breadcrumbs">
  <li><?php echo $this->Html->link('Home', array('controller'=>'years','action' => 'index'));?></li>
  <li class="unavailable"><?php echo $this->Html->link('Édition ' . $this->params["pass"]["0"],'#');?></li>
  <li class="current"><?php echo $this->Html->link('Équipages','#');?></li>
  <li class="unavailable"><?php echo $this->Html->link($school['School']['name'],'#');?></li>
</ul>
<h1>Equipages</h1>
<p><?php echo $this->Html->link('Ajouter un équipage', array('action' => 'add',$this->params["pass"]["0"]));?></p>
<table class="table table-hover" id="user_table">
	<thead>
	    <tr>
	        <th><?php echo $this->Paginator->sort('team_number', 'Numéro'); ?></th>
	        <th><?php echo $this->Paginator->sort('distance', 'Distance (km)'); ?></th>
	        <th><?php echo $this->Paginator->sort('distance_penalty', 'Pénalité (km)'); ?></th>
	        <th><?php echo $this->Paginator->sort('team_name', "Nom d'équipage"); ?></th>
	        <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
	        <th><?php echo $this->Paginator->sort('mobile_phone', 'Numéro portable'); ?></th>
	        <th><?php echo $this->Paginator->sort('member_name_one', 'Membre 1'); ?></th>
	        <th><?php echo $this->Paginator->sort('member_name_two', 'Membre 2'); ?></th>
	        <th><?php echo $this->Paginator->sort('payed', 'Inscription'); ?></th>
	        <th><?php echo $this->Paginator->sort('School.name', 'Ecole'); ?></th>
	        <th>Actions</th>
	    </tr>
	</thead>

<!-- Here's where we loop through our $posts array, printing out post info -->
	<tbody>
	    <?php foreach ($users as $user): ?>
	    <tr>
	        <td>
	        	<?php echo $user['User']['team_number']; ?>
	        </td>
	        <td>
	        	<?php echo $user['User']['distance']; ?>
	        </td>
	        <td>
	        	<?php echo $user['User']['distance_penalty']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['team_name']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['email']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['mobile_phone']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['member_name_one']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['member_name_two']; ?>
	        </td>
	        <td>
	            <?php echo ($user['User']['payed'] ? '<span class="label label-success">validée</span>' : '<span class="label label-important">en cours</span>'); ?>
	        </td>
	        <td>
	            <?php echo $user['School']['name']; ?>
	        </td>
	        <td>
	        	<?php echo $this->Html->link('Modifier', array('action' => 'edit', $this->params["pass"]["0"], $user['User']['id'])); ?>
	            <?php echo $this->Form->postLink(
	                'Supprimer',
	                array('action' => 'delete', $user['User']['id']),
	                array('confirm' => 'Etes vous sûr de vouloir supprimer cet équipage ?'));
	            ?>
	            <?php echo $this->Html->link(
	                'Trajet',
	                array('controller'=>'positions','action' => 'index',$this->params["pass"]["0"], $user['User']['id']));
	            ?>
	        </td>
	    </tr>
	    <?php endforeach; ?>
	    <?php if(empty($users)): ?>
		<tr>
	        <td>Aucun équipage</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>

<?php echo $this->Paginator->numbers();?>
<p>
<?php echo $this->Html->link('Retour', array('controller'=>'years','action' => 'index'));?>
</p>