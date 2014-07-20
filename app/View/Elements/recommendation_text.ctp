<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<p class="recommendation-text">
    <!--nl2br ajoute les sauts de ligne et preg_replace genere les links a partir des urls trouvées--> 
    <?= nl2br($string = preg_replace("/([\w]+:\/\/[\w-?&;!#%~=\.\/\@]+[\w\/])/i","<a target=\"_blank\" href=\"$1\">$1</a>",$recommendation['content'])); ?>
</p>