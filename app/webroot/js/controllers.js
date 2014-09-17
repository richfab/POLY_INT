/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

'use strict';

/* Controllers */

var polyintControllers = angular.module('polyintControllers', []);

polyintControllers.controller('RecommendationCtrl', ['$scope',
    function($scope) {
        
        $scope.submit = function(){
            console.log('save: '+$scope.content+' '+$scope.recommendationId+' '+$scope.recommendationtypeId);
        };
        
        $scope.recommendations = [
            {'id':'1', 'content':'reco 1', 'recommendationtypeId':'3'},
            {'id':'2', 'content':'reco 2', 'recommendationtypeId':'4'}
        ];
        
        $scope.change_recommendationtype = function(i){
            $scope.recommendationtypeId = i;
            $scope.content = '';
            $scope.recommendationId = '';
        };
        
        $scope.edit_recommendation = function(recommendation){
            $scope.content = recommendation.content;
            $scope.recommendationId = recommendation.id;
            $scope.recommendationtypeId = recommendation.recommendationtypeId;
        };
        
    }]);

polyintControllers.controller('SignupCtrl', ['$scope',
    function($scope) {
        $scope.user = {};
        $scope.activeStep = 1;
        
        $scope.schools = [
            {id:'1', name:'Grenoble'},
            {id:'2', name:'Toulouse'}
        ];
        
        $scope.departments = [
            {id:'1', name:'Environnement'},
            {id:'2', name:'Mécanique'}
        ];
        
        $scope.user.school = $scope.schools[0];
        $scope.user.department = $scope.departments[0];
        
        $scope.previous = function(){
            $scope.activeStep--;
        };
        
        $scope.next = function(){
            $scope.activeStep++;
        };
        
        $scope.save = function(){
            console.log('save: '+JSON.stringify($scope.user));
        };
    }]);