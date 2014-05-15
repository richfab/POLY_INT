<div class="" id="presentation">
</div>

<div class="block-color hidden-xs">
    <div class="container">
        <h2>Polytech Abroad est un véritable passeport partagé entre tous les étudiants du réseau Polytech. C'est l'unique plate-forme qui permet de contacter rapidement n'importe quel étudiant de Polytech parti en stage ou en semestre à l'étranger.</h2>
    </div>
</div>
    
<div class="access">
    <?= $this->Html->link(
        'poster une experience',
        array('controller'=>'experiences', 'action'=>'info'),
        array('class'=>'btn btn-default btn-blue',"role"=>"button"));?>
    <?= $this->Html->link(
        'explorer la carte',
        array('controller'=>'experiences', 'action'=>'explore'),
        array('class'=>'btn btn-default btn-orange',"role"=>"button"));?>
</div>

<div class="container marketing">
    <h2 class="mini-marketing">a quoi ça sert <span class="wut">?</span></h2>
    <div class="row row-marketing">
        <div class="col-md-3">
            <?= $this->Html->image('5.png', array('alt' => 'geolocalisation'));?>
            <h2>Geolocalisation</h2>
            <p>Retrouve les étudiants de Polytech en stage, études ou voyage à l'étranger</p>
        </div>
        <div class="col-md-3">
            <?= $this->Html->image('8.png', array('alt' => 'temps'));?>
            <h2>Depuis 2013</h2>
            <p>Remonte le temps et découvre où sont partis les étudiants avant toi</p>
        </div>
        <div class="col-md-3">
            <?= $this->Html->image('14.png', array('alt' => 'retour experience'));?>
            <h2>Retour d'experience</h2>
            <p>Consulte et partage des bons plans sur des destinations du monde entier</p>
        </div>
        <div class="col-md-3">
            <?= $this->Html->image('11.png', array('alt' => 'social'));?>
            <h2>Social</h2>
            <p>Contacte facilement les étudiants déjà présent dans un pays</p>
        </div>
    </div>
</div>

<div class="access">
    <h4 id="linkedin-reco">Échange aussi sur la <a href="https://www.linkedin.com/groups?gid=6576008" target="_blank"><span class="linkedin-name">communauté&nbsp;LinkedIn</span></a> de Polytech Abroad</h4>
</div>