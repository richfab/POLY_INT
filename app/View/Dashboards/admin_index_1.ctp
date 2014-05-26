<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-sm-6" id="col_users" style="text-align: right;">
        <h2><span class="glyphicon glyphicon-user"></span> Users</h2>
        <p>
            Number of active <?= $this->Html->link('users', array('controller' => 'users', 'action' => 'index'));?> : <?= $users_count;?>
        </p>
        <ul>
            <?php foreach ($schools as $school): ?>
                <li style="list-style-type: none;">
                    &emsp;<?= count($school['User']);?> in <?= $this->Html->link($school['School']['name'],array('controller' => 'schools', 'action' => 'view', $school['School']['id'], 'admin' => true));?>
                </li>
            <?php endforeach;?>
        </ul>
        <p>
            Number of <?= $this->Html->link('signup requests', array('controller' => 'users', 'action' => 'index'));?> : <?= $signup_requests_count;?>
        </p>
    </div>
    <div class="col-sm-6">
        <h2><span class="glyphicon glyphicon-globe"></span> Experiences</h2>
        <p>
            Number of <?= $this->Html->link('experiences', array('controller' => 'experiences', 'action' => 'index'));?> : <?= $experiences_count;?>
        </p>
        <ul>
            <li>
                <?= $cities_count;?> different <?= $this->Html->link('cities', array('controller' => 'cities', 'action' => 'index'));?>
            </li>
            <li>
                <?= $countries_count;?> different <?= $this->Html->link('countries', array('controller' => 'countries', 'action' => 'index'));?>
            </li>
        </ul>
        <h2><span class="glyphicon glyphicon-comment"></span> Recommendations</h2>
        <p>
            Number of <?= $this->Html->link('recommendations', array('controller' => 'recommendations', 'action' => 'index'));?> : <?= $recommendations_count;?>
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