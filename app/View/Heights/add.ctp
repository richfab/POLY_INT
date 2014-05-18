<?php
    echo $this->Form->create('Height',array('type'=>'file'));
        echo $this->Form->input('photo_file',array('type'=>'file','label'=>'Prendre ou choisir une photo'));
        echo $this->Form->input('height',array('label'=>'Hauteur du monument :'));
        echo $this->Form->input('comment',array('label'=>'Ajouter un commentaire :','rows' => 3));
?>
<div id="submit">
    <button type="submit" class="btn btn-success">Envoyer</button>
</div>