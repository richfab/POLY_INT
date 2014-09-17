<style type="text/css">
    .css-form input.ng-invalid.ng-dirty {
        background-color: lightcoral;
    }
    
    .css-form input.ng-valid.ng-dirty {
        background-color: lightgreen;
    }
</style>

<div ng-app="polyintApp">
    <div ng-controller="SignupCtrl">
        <h3>STEP : {{activeStep}}</h3>
        <form class="css-form" ng-show="activeStep == 1" name="formstep1" novalidate>
                
            Email<br/>
            <input type="email" ng-model="user.email" name="uEmail" required/><br/>
                
            <a>I dont have a student email</a><br/>
                
            Password<br/>
            <input type="password" ng-model="user.password" ng-minlength=6 name="uPassword" required/><br/>
                
            <button ng-disabled="formstep1.$invalid" ng-click="next()">next</button>
                
        </form>
        <form class="css-form" ng-show="activeStep == 2" name="formstep2" novalidate>
                
            Lastname<br/>
            <input type="text" ng-model="user.firstname" ng-maxlength=20 name="uFirstname" required/><br/>
                
            Firstname<br/>
            <input type="text" ng-model="user.lastname" ng-maxlength=20 name="uLastname" required/><br/>
                
            Polytech<br/>
            <select ng-model="user.school" ng-options="school as school.name for school in schools" required></select><br/>
                            
            Department<br/>
            <select ng-model="user.department" ng-options="department as department.name for department in departments" required></select><br/>
                
            <button ng-click="previous()">previous</button><button ng-disabled="formstep1.$invalid || formstep2.$invalid" ng-click="save()">save</button>
                
        </form>
    </div>
</div>