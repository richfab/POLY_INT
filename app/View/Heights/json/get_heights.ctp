<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
    $data = array();
    foreach ($heights as $key => $height){
        $data[$key]['school_id'] = $height['User']['school_id'];
        $data[$key]['school_name'] = $school_names[$height['User']['school_id']];
        $data[$key]['school_color'] = '#'.$school_colors[$height['User']['school_id']];
        $data[$key]['total'] = $height[0]['total'];
    }
    echo json_encode($data);