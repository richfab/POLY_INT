<p>
    <strong>Bonjour <?php echo $firstname; ?></strong>,
</p>
    
<p>
    Tu as fait une demande de réinitialisation de mot de passe. Pour changer ton mot de passe, clique sur le lien ci-dessous :
</p>
    
<p>
    <?php echo $this->Html->link('Changer mot de passe',$this->Html->url($link,true)); ?>
</p>