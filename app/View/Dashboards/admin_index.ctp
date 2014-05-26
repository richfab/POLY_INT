<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-sm-6" style="text-align: right; padding-right: 50px;">
        <h2><span class="glyphicon glyphicon-user"></span> Users</h2>
        <p>
            Number of active <?= $this->Html->link('users', array('controller' => 'users', 'action' => 'index', 'admin' => true));?> : <?= $users_count;?>
        </p>
        <ul>
            <?php foreach ($schools as $school): ?>
                <li style="list-style-type: none;">
                    &emsp;<?= count($school['User']);?> in <?= $this->Html->link($school['School']['name'],array('controller' => 'schools', 'action' => 'view', $school['School']['id'], 'admin' => true));?>
                </li>
            <?php endforeach;?>
        </ul>
        <p>
            Number of <?= $this->Html->link('signup requests', array('controller' => 'users', 'action' => 'index', 'admin' => true));?> : <?= $signup_requests_count;?>
        </p>
    </div>
    <div class="col-sm-6" style="padding-left: 50px;">
        <h2><span class="glyphicon glyphicon-globe"></span> Experiences</h2>
        <p>
            Number of <?= $this->Html->link('experiences', array('controller' => 'experiences', 'action' => 'index', 'admin' => true));?> : <?= $experiences_count;?>
        </p>
        <ul>
            <li>
                <?= $cities_count;?> different <?= $this->Html->link('cities', array('controller' => 'cities', 'action' => 'index', 'admin' => true));?>
            </li>
            <li>
                <?= $countries_count;?> different <?= $this->Html->link('countries', array('controller' => 'countries', 'action' => 'index', 'admin' => true));?>
            </li>
        </ul>
        <h2><span class="glyphicon glyphicon-comment"></span> Recommendations</h2>
        <p>
            Number of <?= $this->Html->link('recommendations', array('controller' => 'recommendations', 'action' => 'index', 'admin' => true));?> : <?= $recommendations_count;?>
        </p>
        <ul>
            <?php foreach ($recommendationtypes as $recommendationtype): ?>
                <li>
                    &emsp;<?= count($recommendationtype['Recommendation']);?> in <?= $recommendationtype['Recommendationtype']['name'];?>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>