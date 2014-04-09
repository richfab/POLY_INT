<div class="container" id="presentation">
</div>
    
<div class="container marketing">
    <h2 class="mini-marketing">a quoi ça sert <span class="wut">?</span></h2>
    <div class="row row-marketing">
        <div class="col-lg-3">
            <?= $this->Html->image('5.png', array('alt' => 'geolocalisation'));?>
            <h2>Geolocalisation</h2>
            <p>Permet de connaitre la localisation des étudiants polytech en stage ou semestre à l'étranger.</p>
        </div>
        <div class="col-lg-3">
            <?= $this->Html->image('8.png', array('alt' => 'temps'));?>
            <h2>Depuis 2013</h2>
            <p>Remontez le temps et regardez où se situaient les élèves en 2013.</p>
        </div>
        <div class="col-lg-3">
            <?= $this->Html->image('14.png', array('alt' => 'retour experience'));?>
            <h2>Retour d'experience</h2>
            <p>Consultez les avis d'étudiants sur un pays et regardez le classement des meilleures destinations.</p>
        </div>
        <div class="col-lg-3">
            <?= $this->Html->image('11.png', array('alt' => 'social'));?>
            <h2>Social</h2>
            <p>Connectez-vous facilement avec les étudiants déjà présent dans un pays.</p>
        </div>
    </div>
</div>
    
<div class="access">
    <?= $this->Html->link(
        'explorer la carte',
        array('controller'=>'experiences', 'action'=>'explore'),
        array('class'=>'btn btn-default btn-test',"role"=>"button"));?>
</div>
    
    
<div class="block-color">
    <div class="container">
        <h2>"Polytech Explorer, l'unique plate-forme commune à tout le réseau Polytech de France qui permet de contacter rapidement n'importe quel étudiant de Polytech parti en stage ou en semestre à l'étranger. Un véritable passeport partagé entre tous les étudiants."</h2>
    </div>
</div>