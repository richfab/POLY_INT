<?php
    $data = array();
    foreach ($countries as $country):
        $data['countries'][$country['City']['country_id']] = $country[0]['experienceNumber'];
    endforeach;
    foreach ($cities as $city):
        $data['cities']['id'][] = $city['City']['id'];
        $data['cities']['coords'][] = array($city['City']['lat'],$city['City']['lon']);
        $data['cities']['names'][] = $city['City']['name'];
        $data['cities']['experienceNumbers'][] = $city[0]['experienceNumber'];
    endforeach;
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>