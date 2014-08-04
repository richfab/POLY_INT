<h2><?= __('Se connecter');?></h2>
    <?php echo $this->Form->create('User', array(
        'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                        'class' => 'col col-sm-3 control-label'
                ),
                'wrapInput' => 'col col-sm-6',
                'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    )); ?>
        
        <?php echo $this->Form->input('email', array('placeholder' => __('Email'),'label' => __('Email'))); ?>
        <?php echo $this->Form->input('password', array('placeholder' => __('Mot de passe'),'label' => __('Mot de passe'))); ?>
            
<div class="form-group">
            <?php echo $this->Form->submit(__('Se connecter'), array(
                    'div' => 'col col-md-9 col-md-offset-3',
                    'class' => 'btn btn-orange'
            )); ?>
</div>
    
<p><?= $this->Html->link(__('Mot de passe oublié'), array('action' => 'forgotten_password')); ?></p>
<p><?= $this->Html->link(__("S'inscrire"), array('action' => 'signup')); ?></p>
<?php echo $this->Form->end(); ?>