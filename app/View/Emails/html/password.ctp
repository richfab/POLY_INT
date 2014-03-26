<p>
    <strong>Bonjour <?php echo $firstname; ?></strong>
</p>
    
<p>
    Vous avez fait une demande de réinitialisation de mot de passe. Pour changer votre mot de passe, veuillez cliquer sur le lien ci-dessous :
</p>
    
<p>
    <?php echo $this->Html->link('Changer mot de passe',$this->Html->url($link,true)); ?>
</p>