<div ng-controller="SignupCtrl">
    
    <div class="modal fade" id="modalSignup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Sign up</h4>
                </div>
                <div class="modal-body">
                    
                    <form role="form" ng-show="activeStep == 1" name="formstep1" novalidate>
                        
                        <div ng-hide="regularEmail">
                            <div class="form-group" ng-class="{'has-error': formstep1.uEmail.$invalid && formstep1.uEmail.$dirty}">
                                <input type="email" ng-model="user.email" ng-pattern="validEmails" name="uEmail" ng-required="regularEmail == false" placeholder="Your student email" class="form-control"/>
                                <span class="help-block"><a ng-click="useRegularEmail()">I dont have a student email</a></span>
                            </div>
                        </div>  
                            
                        <div ng-show="regularEmail">
                            <div class="form-group" ng-class="{'has-error': formstep1.uEmailAtSignUp.$invalid && formstep1.uEmailAtSignUp.$dirty}">
                                <input type="email" ng-model="user.email_at_signup" name="uEmailAtSignUp" ng-required="regularEmail" placeholder="Your email" class="form-control"/>
                            </div>
                            <div class="form-group" ng-class="{'has-error': formstep1.uLinkedin.$invalid && formstep1.uLinkedin.$dirty}">
                                <input type="url" ng-model="user.linkedin" name="uLinkedin" placeholder="http://fr.linkedin.com/in/pseudo" class="form-control"/>
                                <span class="help-block">Indique ton profil LinkedIn pour réduire le temps de traitement de la demande</span>
                            </div>
                        </div>
                            
                        <div class="form-group" ng-class="{'has-error': formstep1.uPassword.$invalid && formstep1.uPassword.$dirty}">
                            <input type="password" ng-model="user.password" ng-minlength=6 name="uPassword" required placeholder="Choose password" class="form-control"/>
                        </div>
                            
                        <button ng-show="regularEmail" class="btn btn-orange" ng-click="backToStudentEmail()">previous</button> <button class="btn btn-blue" ng-disabled="formstep1.$invalid" ng-click="next()">next</button>
                            
                    </form>
                    <form role="form" ng-show="activeStep == 2" name="formstep2" novalidate>
                        
                        <div class="form-group" ng-class="{'has-error': formstep2.uFirstname.$invalid && formstep2.uFirstname.$dirty}">
                            <input type="text" ng-model="user.firstname" ng-maxlength=20 name="uFirstname" required placeholder="Prénom" class="form-control"/>
                        </div>
                            
                        <div class="form-group" ng-class="{'has-error': formstep2.uLastname.$invalid && formstep2.uLastname.$dirty}">
                            <input type="text" ng-model="user.lastname" ng-maxlength=20 name="uLastname" required placeholder="Nom" class="form-control"/>
                        </div>
                            
                        <div class="form-group">
                            <select ng-model="user.school" ng-options="school as school.School.name for school in schools" required class="form-control">
                                <option value="" selected="selected" disabled>Polytech</option>
                            </select>
                        </div>
                            
                        <div class="form-group">
                            <select ng-model="user.department" ng-options="department as department.Department.name for department in departments" required class="form-control">
                                <option value="" selected="selected" disabled>Department</option>
                            </select>
                        </div>
                            
                        <button class="btn btn-orange" ng-click="previous()">previous</button><button type="submit" class="btn btn-blue" ng-disabled="formstep1.$invalid || formstep2.$invalid" ng-click="save()">Signup</button>
                    </form>
                        
                    <div id="signup" ng-show="activeStep == 3">
                        <h3 ng-show="errorCode">Oops.</h3>
                        <h3 ng-show="errorCode == 0">Wouhou!</h3>
                        <p>{{message}}</p>
                        <a class="icon-facebook-mini" href="https://www.facebook.com/PolytechAbroad" target="_blank"></a>
                        <a class="icon-twitter-mini" href="https://twitter.com/PolytechAbroad" target="_blank"></a>
                        <a class="icon-linkedin-mini" href="https://www.linkedin.com/groups?gid=6576008" target="_blank"></a>
                        <button ng-show="errorCode" class="btn btn-orange" ng-click="previous()">previous</button>
                    </div>
                        
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
        
</div>