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
	<?php echo $this->Html->charset(); ?>
        <title>
		<?php echo $title_description; ?>:
		<?php echo $title_for_layout; ?>
        </title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css(array('cake.generic','bootstrap','admin'));
                
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
            <div id="header">
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-home"></span> Dashboard', array('controller' => 'dashboards', 'action' => 'index','admin'=>true),array('escape'=>false)); ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-user"></span> Users', array('controller' => 'users', 'action' => 'index','admin'=>true),array('escape'=>false)); ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-globe"></span> Experiences', array('controller' => 'experiences', 'action' => 'index','admin'=>true),array('escape'=>false)); ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-comment"></span> Recommendations', array('controller' => 'recommendations', 'action' => 'index','admin'=>true),array('escape'=>false)); ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-book"></span> Schools', array('controller' => 'schools', 'action' => 'index','admin'=>true),array('escape'=>false)); ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-bookmark"></span> Departments', array('controller' => 'departments', 'action' => 'index','admin'=>true),array('escape'=>false)); ?>
                    <?php echo $this->Html->link('Disconnect', array('controller' => 'users', 'action' => 'logout','admin'=>false),array('style' => 'float:right')); ?>
                    <a style="float: right"> | </a>
                    <?php echo $this->Html->link('User site',array('controller'=>'pages', 'action'=>'index','admin'=>false),array('style' => 'float:right')); ?>
            </div>
            <div id="content">
                
			<?php echo $this->Session->flash(); ?>
                
			<?php echo $this->fetch('content'); ?>
            </div>
        </div>
    </body>
</html>
