<?php
    $data = array();
    foreach ($countries as $country):
        $data['countries'][$country['Country']['id']] = $country['Country']['experienceNumber'];
        foreach ($country['City'] as $city):
            if($city['experienceNumber']>0){
                $data['cities']['id'][] = $city['id'];
                $data['cities']['coords'][] = array($city['lat'],$city['lon']);
                $data['cities']['names'][] = $city['name'];
                $data['cities']['experienceNumbers'][] = $city['experienceNumber'];
            }
        endforeach;
    endforeach;
    echo json_encode($data);
?>