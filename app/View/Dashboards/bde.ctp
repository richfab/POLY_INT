<div style="margin-top:40px;margin-bottom:40px;text-align:center">
    <h2>Ton école est-elle prête à conquérir le monde ?</h2>
    <h4 style="color:#56585f">Compare le pourcentage d'étudiants inscrits sur Polytech Abroad dans ton école par rapport aux autres écoles</h4>
</div>
<div class="well">
    <?php foreach ($schools as $school): ?>
    <div class="row">
        <div class="col-sm-3" style="text-align:right">
            <h4 style="margin-top: 0px;"><span style="border-bottom: #<?php echo $school['School']['color'];?> solid 5px;"><?php echo $school['School']['name'];?></span></h4>
        </div>
        <div class="col-sm-7">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $school['School']['percentage'];?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $school['School']['percentage'];?>%;">
                    <?php echo round($school['School']['percentage']);?>%
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <b><?php echo $school['School']['signed_up'];?></b>/<?php echo $school['School']['number_of_students'];?>
        </div>
    </div>
    <?php endforeach;?>
</div>

<div class="well">
    <h2 style="text-align:center">Comment augmenter le nombre d'inscrits dans ton école ?</h2>
    <div class="row" style="text-align:center;">
        <div class="col-md-4">
            <h1><span style="color:rgb(53, 145, 42)" class="glyphicon glyphicon-envelope"></span></h1>
            <p>Envoie un email aux étudiants de ton école pour les informer de l'éxistence de ce site génial.</p>
        </div>
        <div class="col-md-4">
            <h1><span style="color:#3B5998" class="glyphicon glyphicon-thumbs-up"></span></h1>
            <p>Partage la page Facebook Polytech Abroad sur les réseaux sociaux.</p>
            <p><a class="btn btn-default" href="http://facebook.com/PolytechAbroad" target="_blank" role="button">Page Facebook »</a></p>
        </div>
        <div class="col-md-4">
            <h1><span style="color:rgb(216, 41, 68)" class="glyphicon glyphicon-bullhorn"></span></h1>
            <p>Parles-en autour de toi ! Plus on est nombreux à l'utiliser, plus ce sera facile de partir à l'étranger.</p>
        </div>
    </div>
</div>