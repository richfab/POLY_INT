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
        
        $scope.recommendation = {};
        
        $scope.submit = function(){
            console.log('save: '+JSON.stringify($scope.recommendation));
            $scope.recommendation.id = '';
            $scope.recommendation.content = '';
        };
        
        $scope.recommendations = [
            {'id':'1', 'content':'reco 1', 'recommendationtypeId':'3'},
            {'id':'2', 'content':'reco 2', 'recommendationtypeId':'4'}
        ];
        
        $scope.change_recommendationtype = function(i){
            $scope.recommendation.id = '';
            $scope.recommendation.content = '';
            $scope.recommendation.recommendationtype_id = i;
        };
        
        $scope.edit_recommendation = function(recommendation){
            $scope.recommendation.id = recommendation.id;
            $scope.recommendation.content = recommendation.content;
            $scope.recommendation.recommendationtype_id = recommendation.recommendationtypeId;
        };
        
    }]);

polyintControllers.controller('SignupCtrl', ['$scope','School','Department','User',
    function($scope, School, Department, User) {
       
        $scope.user = {};
        $scope.activeStep = 1;
        
        //test
        $scope.user.email = "fab@univ-nantes.fr";
        $scope.user.password = "password";
//        $scope.user.firstname = "Fab";
//        $scope.user.lastname = "Richard";
        
        $scope.validEmails = /\b@(etu.univ-nantes.fr|univ-nantes.fr|polytech-lille.net|etud.univ-montp2.fr|etu.univ-tours.fr|etu.univ-orleans.fr|polytech.upmc.fr|u-psud.fr|etudiant.univ-bpclermont.fr|etu.univ-lyon1.fr|etu.univ-savoie.fr|polytech.unice.fr|etu.univ-provence.fr|etu.univ-amu.fr|e.ujf-grenoble.fr|univ-lyon1.fr)\b/;
        
        $scope.schools = School.query(function() {
            $scope.schools = $scope.schools.schools;
        });
        
        $scope.departments = Department.query(function() {
            $scope.departments = $scope.departments.departments;
        });
        
        $scope.previous = function(){
            $scope.activeStep--;
        };
        
        $scope.next = function(){
            $scope.activeStep++;
        };
        
        $scope.backToStudentEmail = function(){
            $scope.regularEmail = false;
            delete $scope.user.password;
        };
        
        $scope.useRegularEmail = function(){
            $scope.regularEmail = true;
            delete $scope.user.password;
            delete $scope.user.email;
        };
        
        $scope.save = function(){
            $scope.next();
            
            $scope.user.school_id = $scope.user.school.School.id;
            $scope.user.department_id = $scope.user.department.Department.id;
            delete $scope.user.department;
            delete $scope.user.school;
            
            User.save($scope.user,function(data){
                $scope.message = data.message;
                $scope.errorCode = data.errorCode;
            });
            console.log($scope.user);
        };
    }]);