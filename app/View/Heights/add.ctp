<?php
    echo $this->Form->create('Height',array('type'=>'file'));
        echo $this->Form->input('photo_file',array('type'=>'file','label'=>'Prendre ou choisir une photo'));
        echo $this->Form->input('place',array('label'=>'Nom du monument et lieu :','rows' => 2));
?>
<div id="submit">
    <button type="submit" class="btn btn-success">Envoyer</button>
</div>