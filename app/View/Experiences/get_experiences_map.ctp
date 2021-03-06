<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($experiences as $experience): ?>
        <li>
            <div class="row row-experience">
                <div class="col-sm-2 col-xs-3" style="max-width: 74px;">
                    <?php if(!empty($experience['User']['avatar'])) {
                        echo $this->Image->resize($experience['User']['avatar'],61,61,array('alt' => 'avatar','class' => 'avatar','height' => '60px','onload' => "this.style.backgroundColor='#".$school_colors[$experience['User']['school_id']]."'"));
                    } else {
                        echo $this->Html->link($this->Html->image('avatar.png', array('alt' => 'avatar','class' => 'avatar','height' => '61px','onload' => "this.style.backgroundColor='#".$school_colors[$experience['User']['school_id']]."'")),array('controller'=>'users', 'action' => 'profile', $experience['User']['id']),array('escape' => false));
                    }?>
                </div>
                <a href="<?= $this->Html->url(array('controller'=>'users', 'action' => 'profile', $experience['User']['id'],'#' => $experience['Experience']['id']),true);?>">
                    <div class="col-sm-10 col-xs-9">
                        <div style="color: #428bca;"><?= $experience['User']['firstname'].' '.$experience['User']['lastname'];?></div>
                        <div><?= __($experience['Motive']['name']).' - '.$experience['City']['name'];?>, <?= $experience['City']['country_id']; ?></div>
                        <div style="font-size: 12px">
                            <?php
                                echo $this->element('friendly_date', array('date_start'=>$experience['Experience']['dateStart'], 'date_end'=>$experience['Experience']['dateEnd']));
                            ?>
                        </div>
                    </div>
                </a>
            </div>
        </li>
    <?php endforeach;?>
    <?php 
        //si le nombre de resultats est egale a la limite de resultat on affiche un bouton plus
        if(count($experiences) == $result_limit): ?>
        <p style="text-align: center">
            <a style="cursor: pointer" onclick="$(this).remove();get_experiences('get_experiences_map',null,<?= $next_offset; ?>)"><?= __('plus');?></a>
        </p>
    <?php endif;?>
    <?php 
        //si aucune expérience n'a été trouvée
        if(empty($experiences) && $offset === '0'): ?>
        <li>
            <div class="list-map-message">
                Sois le premier à <?= $this->Html->link("poster une expérience", array('controller'=>'experiences', 'action' => 'info'),array("style"=>"display:inline-block")); ?> dans ce pays
            </div>
        </li>
    <?php endif; ?>
<?php else: ?>
        <li>
            <div class="list-map-message">
                <?= $this->Html->link(__("Connecte-toi"), array('controller'=>'users', 'action' => 'login'),array("style"=>"display:inline-block")); ?> <?= __('ou');?> <?= $this->Html->link(__("inscris-toi"), array('controller'=>'users', 'action' => 'signup'),array("style"=>"display:inline-block")); ?> <?= __('pour consulter les expériences');?>
            </div>
        </li>
<?php endif; ?>