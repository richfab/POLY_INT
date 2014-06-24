<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo $this->Html->image(AuthComponent::user('avatar'), array('alt' => 'profile_pic', 'id' => 'cropbox', 'width' => '100%'));
?>
    
<!-- This is the form that our event handler fills -->
<form action="crop.php" method="post" onsubmit="return checkCoords();">
    <input type="hidden" id="x" name="x" />
    <input type="hidden" id="y" name="y" />
    <input type="hidden" id="w" name="w" />
    <input type="hidden" id="h" name="h" />
</form>
    
<script type="text/javascript">
    
    $(function(){
        
        var init_image = $('#cropbox');
        
        init_image.load(function(){
            
            $('#cropbox').Jcrop({
                aspectRatio: 1,
                setSelect:[
                    init_image.width()*0.3,
                    init_image.height()*0.3,
                    init_image.width()*0.7,
                    init_image.height()*0.7
                ],
                onSelect: updateCoords
            });
            
        });
        
    });
    
    function updateCoords(c)
    {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    };
    
    function checkCoords()
    {
        if (parseInt($('#w').val())) return true;
        return false;
    };
    
</script>