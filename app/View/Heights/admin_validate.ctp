<div>
	<h2 class="page-title">Contest validation</h2>
	<?php if (isset($photo)):?>
	<p><b><?= $total_photo_count['validated'];?></b> photo(s) validée(s), <b><?= $total_photo_count['not_validated'];?></b> photo(s) en cours de validation, <b><?= $total_photo_count['rejected'];?></b> photo(s) rejetée(s)</p>
	<p>Photo <b><?= $offset+1;?>/<?= $total_photo_count['all'];?></b></p>
	<p><?= $this->Html->image('heights-photos/' . $photo['Height']['url']);?></p>
	<p>Autres photos validées à <b><?= $photo['Height']['city'];?>, <?= $photo['Height']['country'];?></b> :</p>
	<p><?php if($similar_photos): 
		foreach ($similar_photos as $similar_photo):?>
		<?php echo $this->Html->link($this->Html->image('heights-photos/S_' . $similar_photo['Height']['url'], array('title' => $similar_photo['Height']['place'].' ('.$similar_photo['Height']['height'].'m)'.' - '.$similar_photo['Height']['created'])), 
			'/app/webroot/img/heights-photos/'.$similar_photo['Height']['url'], array('escapeTitle' => false));?></a>
	<?php endforeach;?>
	<?php else:?>
		Aucune photo
	<?php endif;?>
	</p>
	<h3>Utilisateur</h3>
	<p><b><?= $photo['User']['firstname']. " " .$photo['User']['lastname'];?> </b>(<?= $photo['User']['email'];?>) – <b>Polytech <?= $photo['User']['School']['name'];?></b></p>
	<p><mark><b><?= $user_photo_count['validated'];?></b> photo(s) validée(s)</mark>, <mark><b><?= $user_photo_count['not_validated'];?></b> photo(s) en cours de validation</mark>, <mark><b><?= $user_photo_count['rejected'];?></b> photo(s) rejetée(s)</mark></p>
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
		echo $this->Html->link('<<', array('action'=>'nav'));
		echo " ";
		if($offset > 0){
			echo $this->Html->link('<', array('action'=>'nav', $offset-1));
		}
		else{
			echo "<";
		}
		echo " | ";
		if($offset+1 < $total_photo_count['all']){
			echo $this->Html->link('>', array('action'=>'nav', $offset+1));
		}
		else{
			echo ">";
		}
		echo " ";
		echo $this->Html->link('>>', array('action'=>'nav', $total_photo_count['all']-1));
	?>
	<?php else:?>
		<p>Toutes les photos sont validées.</p>
	<?php endif;?>
</div>