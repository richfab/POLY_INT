<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2>Users</h2>
<p>
    Number of active <?= $this->Html->link('users', array('controller' => 'users', 'action' => 'index'));?> : <?= $users_count;?>
</p>
<p>
    Number of <?= $this->Html->link('signup requests', array('controller' => 'users', 'action' => 'index'));?> : <?= $signup_requests_count;?>
</p>
<h2>Experiences</h2>
<p>
    Number of <?= $this->Html->link('experiences', array('controller' => 'experiences', 'action' => 'index'));?> : <?= $experiences_count;?>
</p>
<p>
    &emsp;in <?= $cities_count;?> different <?= $this->Html->link('cities', array('controller' => 'cities', 'action' => 'index'));?>
</p>
<p>
    &emsp;in <?= $countries_count;?> different <?= $this->Html->link('countries', array('controller' => 'countries', 'action' => 'index'));?>
</p>
<h2>Recommendations</h2>
<p>
    Number of <?= $this->Html->link('recommendations', array('controller' => 'recommendations', 'action' => 'index'));?> : <?= $recommendations_count;?>
</p>
<p>
    <?php foreach ($recommendationtypes as $recommendationtype): ?>
        <p>
            &emsp;<?= count($recommendationtype['Recommendation']);?> in <?= $recommendationtype['Recommendationtype']['name'];?>
        </p>
    <?php endforeach;?>
</p>