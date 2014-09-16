<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.24/angular.js"></script>
<script type="text/javascript" src="https://code.angularjs.org/1.2.24/angular-route.js"></script>
<script type="text/javascript" src="https://code.angularjs.org/1.2.24/angular-resource.js"></script>
<script src="../js/controllers.js" type="text/javascript"></script>
<script src="../js/app.js" type="text/javascript"></script>
    
<div ng-app="polyintApp">
<?php foreach ($experiences as $experience): ?>
    <div ng-init="experienceId='<?php echo $experience['Experience']['id'];?>'">
        <div ng-controller="RecommendationCtrl">
            
            <span ng-repeat="i in [0,1,2,3]">
                <a ng-click="change_recommendationtype(i)">{{i}}</a>
            </span>
            <br/>
            
            <form ng-submit="submit()">
                <input ng-model="content"/><a>id: {{recommendationId}}</a> | <a>type: {{recommendationtypeId}}</a><input type="submit" value="share" />
            </form>
                
            <p>Expérience <?php echo $experience['Experience']['id'];?></p>
            <ul>
                <li ng-repeat="recommendation in recommendations" ng-click="edit_recommendation(recommendation)">{{recommendation.id}}. {{recommendation.content}}</li>
            </ul>
        </div>
    </div>
<?php endforeach;?>
</div>