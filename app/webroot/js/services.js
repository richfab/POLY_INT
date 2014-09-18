var polyintServices = angular.module('polyintServices', ['ngResource']);

polyintServices.factory('School', ['$resource',
    function($resource){
        return $resource('http://localhost:8888/POLY_INT/schools.json', {}, {
            query: {method:'GET', isArray:false}
        });
    }]);

polyintServices.factory('Department', ['$resource',
    function($resource){
        return $resource('http://localhost:8888/POLY_INT/departments.json', {}, {
            query: {method:'GET', isArray:false}
        });
    }]);

polyintServices.factory('User', ['$resource',
    function($resource){
        return $resource('http://localhost:8888/POLY_INT/users.json', {}, {
            
        });
    }]);