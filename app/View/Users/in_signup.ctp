<h2>S'inscrire</h2>
    
<?php 
    echo $this->Form->create('User', array(
        'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                        'class' => 'col col-sm-3 control-label'
                ),
                'wrapInput' => 'col col-sm-6',
                'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    ));
    echo $this->Form->input('firstname', array('placeholder' => 'Prénom','label' => 'Prénom', 'disabled'=>'disabled'));
    echo $this->Form->input('lastname', array('placeholder' => 'Nom','label' => 'Nom', 'disabled'=>'disabled'));
    echo $this->Form->input('School.id');
    echo $this->Form->input('school_id', array('placeholder' => 'Polytech','label' => 'Polytech'));
    echo $this->Form->input('Department.id');
    echo $this->Form->input('department_id', array('placeholder' => 'Spécialité','label' => 'Spécialité'));?>
        
<div class="form-group">
        <?php echo $this->Form->submit("S'inscrire", array(
                'div' => 'col col-md-9 col-md-offset-3',
                'class' => 'btn btn-blue'
        )); ?>
</div>
<?php echo $this->Form->end(); ?>
    
<script type="text/javascript" src="http://platform.linkedin.com/in.js">
    api_key: 77t2jesmha6ulv
    onLoad: onLinkedInLoad
    authorize: true
</script>
    
<script>
    function onLinkedInLoad() {
        IN.Event.on(IN, "auth", onLinkedInAuth);
    }
    
    function onLinkedInAuth() {
        IN.API.Profile("me")
                .result(function profileCallBack(profiles){
                    member = profiles.values[0];
                    document.getElementById("UserFirstname").value = member.firstName;
                    document.getElementById("UserLastname").value = member.lastName;
                });
        
        IN.API.Raw("people/~/group-memberships")
                .result(function groupMembershipsCallBack(result) {
                    console.log(result);
                    var isMOPN = false;
                    for(var i in result.values){
                        if(result.values[i].group.id === '45291'){
                            isMOPN = true;
                        }
                    }
                    if(isMOPN){
                        alert('is member of Polytech Network');
                    }
                    else{
                        alert('is NOT member of Polytech Network');
                    }
                });
    }
</script>
    
<script type="in/Login"></script>
    
<div id="profiles"></div>