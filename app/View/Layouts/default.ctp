<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$title_description = "Polytech Abroad : L'unique passeport partagé entre tous les étudiants de Polytech";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $this->Html->charset(); ?>
        <title>
		<?php echo $title_description ?>:
		<?php echo $title_for_layout; ?>
        </title>
	<?php
		echo $this->Html->meta('icon');
                
                echo $this->Html->css(array('bootstrap','default'));
                echo $this->Html->script(array('jquery-1.11.0.min','bootstrap')); // Inclut la librairie Jquery
                
                //on inclut les fichiers js qui sont spécifiques a une vue
		if(isset($jsIncludes)){
		    echo $this->Html->script($jsIncludes);
		}
		
		//on inclut les fichiers css qui sont spécifiques a une vue
		if(isset($cssIncludes)){
			echo $this->Html->css($cssIncludes);
		}

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    </head>
    <body>
        
        <div id="container">
            
            <div class="navbar navbar-default navbar-fixed-top" id="top-bar">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <?= $this->Html->link(
                                $this->Html->image('mini-logo.png', array('alt' => 'Logo','height'=>'24px')),
                                array('controller'=>'pages', 'action'=>'index'),
                                array('class'=>'navbar-brand text-hide','escape' => false));?>
                    </div>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="menu-item">
                                <?= $this->Html->link('<span class="glyphicon glyphicon-home"></span> accueil',array('controller'=>'pages', 'action'=>'index'),array('style'=>'display:inline-block','escape'=>false));?>
                            </li>
                            <li class="menu-separator"></li>
                            <li class="menu-item">
                                <?= $this->Html->link('<span class="glyphicon glyphicon-globe"></span> explorer',array('controller'=>'experiences', 'action'=>'explore'),array('style'=>'display:inline-block','escape'=>false));?>
                            </li>
                            <li class="menu-separator"></li>
                            <li class="menu-item">
                                <?= $this->Html->link('<span class="glyphicon glyphicon-comment"></span> partager',array('controller'=>'recommendations', 'action'=>'search'),array('style'=>'display:inline-block','escape'=>false));?>
                            </li>
                            <li class="menu-separator"></li>
                            <li class="menu-item">
                                <?= $this->Html->link('<span class="glyphicon glyphicon-search"></span> rechercher',array('controller'=>'experiences', 'action'=>'search'),array('style'=>'display:inline-block','escape'=>false));?>
                            </li>
                            <li class="menu-separator"></li>
                            <li class="menu-item">
                                <?= $this->Html->link('<span class="glyphicon glyphicon-info-sign"></span> a propos',array('controller'=>'pages', 'action'=>'about'),array('style'=>'display:inline-block','escape'=>false));?>
                            </li>
                            <li class="menu-separator"></li>
                            <?php if(AuthComponent::user('id')): ?>
                                <li>
                                    <?= $this->Html->link(
                                            'mon profil',
                                            array('controller'=>'users', 'action'=>'profile'),
                                            array('class'=>'btn btn-default btn-blue'));?>
                                </li>
                                <li>
                                    <?= $this->Html->link(
                                            'déconnexion',
                                            array('controller'=>'users', 'action'=>'logout'),
                                            array('class'=>'btn btn-default btn-orange'));?>
                                </li>
                            <?php else:?>
                                <li>
                                    <?= $this->Html->link(
                                            'inscription',
                                            array('controller'=>'users', 'action'=>'signup'),
                                            array('class'=>'btn btn-default btn-blue'));?>
                                <li>
                                    <?= $this->Html->link(
                                            'connexion',
                                            array('controller'=>'users', 'action'=>'login'),
                                            array('class'=>'btn btn-default btn-orange'));?>
                                </li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <?php if(($this->params['controller']=='pages' && $this->params['action']=='display' && $this->params['pass'][0]=='home')||($this->params['controller']=='experiences' && $this->params['action']=='explore')): ?>
                <div id="content">
            <?php else: ?>
                <div id="content" class="container">
            <?php endif;?>
                    
                <?php echo $this->Session->flash(); ?>

                <?php echo $this->fetch('content'); ?>
            </div>
            
            <div id="footer">
                <div class="container">
                    <p class="pull-left"><a href="http://fabienrichard.fr"></a></p>
                    <p class="pull-right"><a href="./mentions-legales.html">mentions légales</a> | <a href="./conditions-utilisations.html">conditions d'utilisations</a></p>
                </div>
            </div>
            
        </div>
        
        <?php echo $this->Js->writeBuffer(); // Écrit les scripts en mémoire cache ?>
    </body>
</html>
