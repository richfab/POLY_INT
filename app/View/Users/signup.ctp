<?php 
echo $this->Form->create('User', array(
    'id' => 'signupForm',
    ));?>
    <ul id="progressbar">
        <li class="active">Account Setup</li>
        <li>Social Profiles</li>
        <li>Personal Details</li>
    </ul>
    <fieldset>
        <h2 class="fs-title">Create your account</h2>
        <h3 class="fs-subtitle">This is step 1</h3>
        <?php 
        echo $this->Form->input('email', array('placeholder' => __('Email étudiant'),'label' => false,
            'afterInput'=>"<span class='help-block'>".__("Je n'ai pas d'email étudiant. ").$this->Html->link(__("Faire une demande d'inscription"), array('action' => 'signup_request'))."</span>"
            ));
        echo $this->Form->input('password', array('placeholder' => __('Mot de passe'),'label' => false));
        echo $this->Form->input('password_confirme', array('placeholder' => __('Confirmation'), 'label' => false,"type"=>"password"));
        ?>
        <input type="button" name="next" class="next action-botton" value="Next" />

    </fieldset>
    <fieldset>
        <h2 class="fs-title">Social Profiles</h2>
        <h3 class="fs-subtitle">Your presence on the social network</h3>
        <?php 
        echo $this->Form->input('firstname', array('placeholder' => __('Prénom'),'label' => false));
        echo $this->Form->input('lastname', array('placeholder' => __('Nom'),'label' => false));
        echo $this->Form->input('linkedin', array('placeholder' => __('Linkedin'),'label' => false));
        echo $this->Form->input('facebook', array('placeholder' => __('Facebook'),'label' => false));
        ?>
        <input type="button" name="previous" class="previous action-botton" value="Previous" />
        <input type="button" name="next" class="next action-botton" value="Next" />

    </fieldset>
    <fieldset>
        <h2 class="fs-title">Personal Details</h2>
        <h3 class="fs-subtitle">We will never sell it</h3>
        <?php
        echo $this->Form->input('School.id');
        echo $this->Form->input('school_id', array('placeholder' => 'Polytech','label' => false));
        echo $this->Form->input('Department.id');
        echo $this->Form->input('department_id', array('placeholder' => __('Spécialité'),'label' => false));
        ?>
        <input type="button" name="previous" class="previous action-botton" value="Previous" />
        <?php echo $this->Form->submit(__("S'inscrire")); ?>

    </fieldset>
    
    <?php echo $this->Form->end(); ?>

<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
<script>
    //Jquery time
var current_fs,next_fs,previous_fs;//fieldsets
var left,opacity,scale;//fieldset properties which we will animate
var animating;//flag to prevent quick multi-click glitches 

$(".next").click(function(){
  if(animating){
    return false;
  }
  animating=true;
  
  current_fs = $(this).parent();
  next_fs = $(this).parent().next();
  
  //activate next step on progressbar using the index of next_fs
  $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
  
  //show the next fieldset
  next_fs.show();
  //hide the current fieldset width style
  current_fs.animate({opacity:0},{
    step:function(now,mx){
      //as the opacity of current_fs reduces to 0
      //1.scale current_fs down to 80%
      scale = 1-(1-now)*0.2;
      //2.bring next_fs from the right(50%)
      left = (now*50)+"%";
      //3.increase opacity of next_fs to 1 as it moves in 
      opacity = 1-now;
      current_fs.css({'transform':'scale('+scale+')'});
      next_fs.css({'left':left,'opacity':opacity});
    },
    duration:800,
    complete:function(){
      current_fs.hide();
      animating = false;
    },
    //this comes from the custom easing plugin
    easing: 'easeInOutBack'
  });
});


$(".previous").click(function(){
  if(animating){return false;}
  animating=true;
  
  
  current_fs= $(this).parent();
  previous_fs = $(this).parent().prev();
  
  //activate next step on progressbar using the index of next_fs
  $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
  
  //show the next fieldset
  previous_fs.show();
  //hide the current fieldset width style
  current_fs.animate({opacity:0},{
    step:function(now,mx){
      //as the opacity of current_fs reduces to 0
      //1.scale current_fs down to 80%
      scale = 0.8+(1-now)*0.2;
      //2.bring next_fs from the right(50%)
      left = ((1-now)*50)+"%";
      //3.increase opacity of next_fs to 1 as it moves in 
      opacity = 1-now;
      current_fs.css({'left':left});
      previous_fs.css({'transform':'scale('+scale+')','opacity':opacity});
    },
    duration:800,
    complete:function(){
      current_fs.hide();
      animating = false;
    },
    //this comes from the custom easing plugin
   easing: 'easeInOutBack'
  })
})

$('.submit').click(function(){
  return false;
})
</script>
