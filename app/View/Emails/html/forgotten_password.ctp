<p>
    <strong>Bonjour <?php echo $firstname; ?></strong>,
</p>
    
<p>
    Tu as fait une demande de réinitialisation de mot de passe. Pour changer ton mot de passe, clique sur le lien ci-dessous :
</p>
    
<p>
    <?php echo $this->Html->link('Changer mot de passe',$this->Html->url($link,true)); ?>
</p>

<p>
    ou copie ce lien dans la barre d'adresse de ton navigateur : <?= $this->Html->url($link,true);?>
</p>

<p>
    A bientôt sur <?php echo $this->Html->link('Polytech Abroad', $this->Html->url(array('controller' => 'pages', 'action' => 'home'),true)); ?>
</p>