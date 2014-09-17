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