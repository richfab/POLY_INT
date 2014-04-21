<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($experiences as $experience): ?>
        <li>
            <div class="row">
                <div class="col-xs-2">
                    <?= $this->Html->image('avatar.png', array('alt' => 'avatar','class' => 'avatar','height' => '60px','onload' => "this.style.backgroundColor='#".$experience['User']['School']['color']."'"));?>
                </div>
                <div class="col-xs-10">
                    <p><?= $this->Html->link($experience['User']['firstname'].' '.$experience['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $experience['User']['id']));?></p>
                    <p><?= $experience['Motive']['name'].' ('.round($experience[0]['monthDiff'],0).' mois)'.' - '.$experience['City']['name'];?>, <?= $experience['City']['country_id']; ?></p>
                    <p style="font-size: 12px">
                        <?php
                            $today = date_create(date('Y-m-d'));
                            $date_start = date_create($experience['Experience']['dateStart']);
                            $date_end = date_create($experience['Experience']['dateEnd']);
                            //en ce moment
                            if($today >= $date_start && $today <= $date_end){
                                echo 'En ce moment';
                            }
                            //passée
                            if($date_end < $today && $date_start < $today){
                                $interval = date_diff($date_end,$today);
                                //il y a quelques jours
                                if($interval->days <= 31){
                                    $interval_nice = 'quelques jours';
                                }
                                //il y a 1 an
                                elseif ($interval->days >= 365 && $interval->days < 730) {
                                    $interval_nice = $interval->format('%y an');
                                }
                                //il y a plus d'un an
                                elseif ($interval->days >= 730) {
                                    $interval_nice = $interval->format('%y ans');
                                }
                                //il y a x mois
                                else{
                                    $interval_nice = $interval->format('%m mois');
                                }
                                echo "Il y a ".$interval_nice;
                            }
                            //a venir
                            if($date_start > $today && $date_end > $today){
                                $interval = date_diff($date_start,$today);
                                //il y a quelques jours
                                if($interval->days <= 31){
                                    $interval_nice = 'quelques jours';
                                }
                                //il y a 1 an
                                elseif ($interval->days >= 365 && $interval->days < 730) {
                                    $interval_nice = $interval->format('%y an');
                                }
                                //il y a plus d'un an
                                elseif ($interval->days >= 730) {
                                    $interval_nice = $interval->format('%y ans');
                                }
                                //il y a x mois
                                else{
                                    $interval_nice = $interval->format('%m mois');
                                }
                                echo 'Commence dans '.$interval_nice;
                            }
                        ?>
                    </p>
                </div>
            </div>
        </li>
    <?php endforeach;?>
    <?php 
        //si le nombre de resultats est egale a la limite de resultat on affiche un bouton plus
        if(count($experiences) == $result_limit): ?>
        <p style="text-align: center">
            <a style="cursor: pointer" onclick="this.remove();get_experiences('get_experiences_map')">plus</a>
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
                <?= $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login'),array("style"=>"display:inline-block")); ?> ou <?= $this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup'),array("style"=>"display:inline-block")); ?> pour consulter les expériences
            </div>
        </li>
<?php endif; ?>