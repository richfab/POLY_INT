<p>
    <strong>Bienvenue <?php echo $firstname; ?>,</strong>
</p>
    
<p>
    Ton inscription à Polytech Abroad a bien été prise en compte !
</p>

<p>
    Pour activer ton compte, clique sur ce lien :
</p>

<p>
    <?php echo $this->Html->link('Activer mon compte', $this->Html->url($activation_link,true)); ?>
</p>
<p>
    ou copie ce lien dans la barre d'adresse de ton navigateur : <?= $this->Html->url($activation_link,true);?>
</p>

<p>
    A bientôt parmi nous sur <?php echo $this->Html->link('Polytech Abroad', $this->Html->url(array('controller' => 'pages', 'action' => 'home'),true)); ?>
</p>