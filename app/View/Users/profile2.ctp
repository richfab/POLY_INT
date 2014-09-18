<?php foreach ($experiences as $experience): ?>
    <div ng-init="experienceId='<?php echo $experience['Experience']['id'];?>'">
        <div ng-controller="RecommendationCtrl">
            
            <span ng-repeat="i in [0,1,2,3]">
                <a ng-click="change_recommendationtype(i)">{{i}}</a>
            </span>
            <br/>
            
            <form ng-submit="submit()" name="formRecommendation">
                <input ng-model="recommendation.content" required/><a>id: {{recommendation.id}}</a> | <a>type: {{recommendation.recommendationtype_id}}</a>
                <input type="submit" value="share" ng-disabled="formRecommendation.$invalid"/>
            </form>
                
            <p>Expérience <?php echo $experience['Experience']['id'];?></p>
            <ul>
                <li ng-repeat="recommendation in recommendations" ng-click="edit_recommendation(recommendation)">{{recommendation.id}}. {{recommendation.content}}</li>
            </ul>
        </div>
    </div>
<?php endforeach;?>