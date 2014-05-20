<?php
    $today = date_create(date('Y-m-d'));
    //en ce moment
    if($today >= $date_start && $today <= $date_end){
        echo 'En ce moment';
    }
    //passée
    if($date_end < $today && $date_start < $today){
        $interval = date_diff($date_end,$today);
        //il y a quelques jours
        if($interval->days <= 31){
            $interval_nice = 'quelques jours';
        }
        //il y a 1 an
        elseif ($interval->days >= 365 && $interval->days < 730) {
            $interval_nice = $interval->format('%y an');
        }
        //il y a plus d'un an
        elseif ($interval->days >= 730) {
            $interval_nice = $interval->format('%y ans');
        }
        //il y a x mois
        else{
            $interval_nice = $interval->format('%m mois');
        }
        echo "Il y a ".$interval_nice;
    }
    //a venir
    if($date_start > $today && $date_end > $today){
        $interval = date_diff($date_start,$today);
        //il y a quelques jours
        if($interval->days <= 31){
            $interval_nice = 'quelques jours';
        }
        //il y a 1 an
        elseif ($interval->days >= 365 && $interval->days < 730) {
            $interval_nice = $interval->format('%y an');
        }
        //il y a plus d'un an
        elseif ($interval->days >= 730) {
            $interval_nice = $interval->format('%y ans');
        }
        //il y a x mois
        else{
            $interval_nice = $interval->format('%m mois');
        }
        echo 'Dans '.$interval_nice;
    }
?>