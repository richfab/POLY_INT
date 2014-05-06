//tableau de compteurs pour l'affichage temporisé du logo de chargement (loader) utile pour la carte
var timeoutID = [];

function stop_logo_fly(){
    //on annule le compteur pour le cas ou la réponse à été plus rapide que le délais pour afficher le loader
    window.clearTimeout(timeoutID.pop());
    //on cache le loader
    $("#brand_logo").one('animationiteration webkitAnimationIteration', function() {
        $(this).removeClass('fly');
    });
}

//affiche un gif de chargement au milieu de la carte après un certain délais
function start_logo_fly(){
    //on ajoute un compteur au tableau de compteurs
    timeoutID.push(window.setTimeout(function(){
        $('#brand_logo').addClass('fly');
    }, 500));
}