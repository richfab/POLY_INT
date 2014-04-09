<ul class="list-unstyled">
    <?php if(AuthComponent::user('id')) : ?>
        <?php foreach ($experiences as $experience): ?>
            <li>
                <?= $this->Html->image('avatar.jpg', array('alt' => 'avatar')).'<span class="name">'.$this->Html->link($experience['User']['firstname'].' '.$experience['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $experience['User']['id']),array("style"=>"display:inline-block")).'</span> | <span class="type">'.$experience['Motive']['name'].' ('.round($experience[0]['monthDiff'],0).' mois)'.' - '.$experience['City']['name'];?>, <?= $experience['City']['country_id'].'</span>'; ?>
            </li>
        <?php endforeach;?>
        <?php if(empty($experiences)): ?>
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
</ul>