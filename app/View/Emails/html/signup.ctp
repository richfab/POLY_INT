<p>
    <strong>Bienvenue <?php echo $firstname; ?>,</strong>
</p>
    
<p>
    Ton inscription à Polytech Expats a bien été prise en compte !
</p>

<p>
    Pour activer ton compte, clique sur ce lien :
</p>

<p>
    <?php echo $this->Html->link('Activer mon compte', $this->Html->url($activation_link,true)); ?>
</p>