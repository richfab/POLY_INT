<div>
	<h2 class="page-title">Contest validation</h2>
	<?php if (isset($photo)):?>
	<p><?= $this->Html->image('heights-photos/' . $photo['Height']['url']);?></p>
	<p>Utilisateur : <?= $photo['User']['firstname']. " " .$photo['User']['lastname'];?> (<?= $photo['User']['email'];?>) – Polytech <?= $photo['User']['School']['name'];?></p>
	<p><a href="https://www.google.fr/?gws_rd=cr&ei=IoqA#q=hauteur <?php echo $photo['Height']['place'].' '.$photo['Height']['city'];?>" target="_blank">Recherche google de la hauteur</a>
	<?php 
		echo $this->Form->create('Height');
		echo $this->Form->input('id', array('type' => 'hidden', 'value' => $photo['Height']['id']));
		echo $this->Form->input('place',array('label'=>'Monument', 'value' => $photo['Height']['place']));
		echo $this->Form->input('height',array('label'=>'Height', 'value' => $photo['Height']['height']));
		echo $this->Form->input('city',array('label'=>'City', 'value' => $photo['Height']['city']));
		echo $this->Form->input('country',array('label'=>'Country', 'value' => $photo['Height']['country']));
		echo $this->Form->input('verified',array('label'=>'Verified (0=En cours, 1=Validée, -1=Rejetée)', 'value' => $photo['Height']['verified']));
		echo $this->Form->end('Submit');
		echo $this->Html->link('Next', array('action'=>'next', $offset+1));
	?>
	<?php else:?>
		<p>Toutes les photos sont validées.</p>
	<?php endif;?>
</div>