<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */?>

<h2>Database diagram</h2>
<?php 
    echo $this->Html->image('/api_generator/img/dbdiagram.png', array('alt' => 'database diagram'));
?>
<h4>Users</h4>
<ul style="list-style: initial">
    <li>role : 'user' or 'admin'</li>
    <li>email : used for login</li>
    <li>email_at_signup : email used at sign up is stored</li>
    <li>email_is_hidden : '0' means email is not hidden in profile, '1' means it is hidden</li>
    <li>linkedin : linkedin profile URL</li>
    <li>active : '0' means user has not activated his account, '1' means user has activated his account</li>
</ul>

<h4>Experiences</h4>
<ul style="list-style: initial">
    <li>dateStart : start date of the experience</li>
    <li>dateEnd : end date of the experience</li>
    <li>establishment : company, school or organization name</li>
    <li>description : description of the experience</li>
    <li>comment : user's comment on his experience</li>
</ul>

<h4>Recommendations <small>(bons plans)</small></h4>
<ul style="list-style: initial">
    <li>content : content of the recommendation</li>
</ul>

<h4>Recommendation Types</h4>
<ul style="list-style: initial">
    <li>name : name of the recommendation type</li>
    <li>icon : icon of the recommendation type</li>
    <li>description : description of the recommendation type</li>
</ul>

<h4>Schools</h4>
<ul style="list-style: initial">
    <li>name : name of the school (Nantes, Lille, etc)</li>
    <li>color : hex color code of the school</li>
</ul>

<h4>Departments <small>(domaine d'étude, spécialité)</small></h4>
<ul style="list-style: initial">
    <li>name : name of the department</li>
</ul>

<h4>Motives <small>(motif de l'expérience)</small></h4>
<ul style="list-style: initial">
    <li>name : name of the motive (Stage, études, voyage)</li>
</ul>

<h4>Type Notifications <small>(fréquence de notification)</small></h4>
<ul style="list-style: initial">
    <li>name : name of the typenofication (Si quelqu'un est dans la même ville, dans le même pays, jamais)</li>
</ul>

<h4>Cities</h4>
<ul style="list-style: initial">
    <li>name : name of the city</li>
    <li>lat : latitude of the city</li>
    <li>lon : longitude of the country</li>
    <li>experienceNumber : number of experiences in the city</li>
</ul>

<h4>Countries</h4>
<ul style="list-style: initial">
    <li>id : country code (FR for France)</li>
    <li>name : name of the country</li>
    <li>experienceNumber : number of experiences in the country</li>
</ul>