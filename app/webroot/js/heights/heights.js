(function() {
    
    var unselectedColor = "#285C68";
    
    //variable de durée d'animation em ms
    var animationDuration = 1000;
    var delayDuration = 50;
    
    d3.json("heights/get_heights.json", function(error, data) {
        data.forEach(function(d) {
            d.total = +d.total;
        });
        
        //variable pour ne lancer la transition du chart1 que une fois
        var chartHasBeenShowed = false;
        
        $(window).bind("scroll", function(event) {
            
            if(!chartHasBeenShowed){
                
                $("#bar-chart-limit:in-viewport").each(function() {
                    chartHasBeenShowed = true;
                    //barChart init
                    init();
                    displayFirstWebsite();
                    get_heights_gallery_all();
                });
            }
        });
        
        function init(){
            
            var barPad = 5;
            var barWidth = 50;
            
            var w = (barWidth+barPad)*data.length;
            var h = 270;
            
            var scale = d3.scale.linear()
                    .domain([0, d3.max(data, function(d) { return d.total; })]) //definition du domaine (input) 
                    .range([0, h]); //definiation de l'affichage (output)
            
            var svg = d3.select("#bar-chart")
                    .append("svg")
                    .attr("width", w)
                    .attr("height", h);
            
            var websiteLis = d3.select("#webisteUl").selectAll("li")
                    .data(data)
                    .enter()
                    .append("li");
            
            var data_alphabetical = data;
            
            data_alphabetical.sort(function(obj1, obj2) {
                // Ascending: first age less than the previous
                return obj1.school_name > obj2.school_name ? 1 : -1;
            });
            
            var bars = svg.selectAll("rect")
                    .data(data_alphabetical)
                    .enter()
                    .append("rect")
                    .on("mouseover", changeWebsite)
                    .on("click", get_heights_gallery);
            
            //nom du site
            websiteLis
                    .html(
                    function(d,i){
                        return '<span class="website site'+i+'">'+d.total+'m - '+d.school_name+'</span>';
            });
            
            //données et evts
            websiteLis
                    .attr("class","websiteLi")
                    .attr("index", function(d) {
                        return d.school_id;
            })
                    .attr("total", function(d, i) {
                        return d.total;
            })
                    .attr("color", function(d, i) {
                        return d.school_color;
            })
                    .on("mouseover", changeWebsite)
                    .on("click", get_heights_gallery);
            
            
            bars
                    .attr("x", function(d, i) {
                        return i * (w / data.length);
            })
                    .attr("fill",function(d,i){
                        return d.school_color;
            })
                    .attr("color", function(d, i) {
                        return d.school_color;
            })
                    .attr("index", function(d) {
                        return d.school_id;
            })
                    .attr("class","bar")
                    .attr("width", w / data.length - barPad)
                    .attr("height",0)		
                    .attr("y",h)
                    .transition()
                    .delay(function (d,i){ return i * delayDuration;})
                    .duration(animationDuration)
                    .attr("y", function(d) { return h-scale(d.total); })
                    .attr("height", function(d) {
                        return scale(d.total);
            });
            
            //on indique que la transition est terminée
            var barTimeout = setTimeout(function() {
                bars.attr("class","bar animationComplete");
            }, animationDuration+delayDuration*data.length);
        }
        
        //////////////////BOTH//////////////////
        
        // this function will be run everytime we mouse over an element
        var changeWebsite = function() {
            
            //on remet toutes les bars a l'opacité normale
            var bars = d3.selectAll(".bar.animationComplete");
            bars.transition().duration(300)
                    .attr("stroke", "none");
            
            //on selectionne la bar correspondante
            var bar = d3.select(".bar.animationComplete[index='"+$(this).attr("index")+"']");
            bar.transition().duration(300)
                    .attr("stroke", "black");
            
            //on remet tous les noms des sites en normal
            $(".websiteLi span").removeClass("selected");
            
            //on selectionne le site correspondant
            var websiteLiName = $(".websiteLi[index='"+$(this).attr("index")+"'] span");
            websiteLiName.addClass("selected");
            
            //on change les données
            var websiteLi = $(".websiteLi[index='"+$(this).attr("index")+"']");
            
            $("#dataHeight").text(dataNumberToNice(websiteLi.attr("total")));
        };
        
        function get_heights_gallery_all(){
            get_gallery({});
        }
        
        function get_heights_gallery(){
            var filter = {school_id : $(this).attr("index")};
            get_gallery(filter);
        }
        
        function get_gallery(filter){
            
            start_logo_fly();
            
            $.ajax({
                type:"POST",
                url : "heights/get_heights_gallery",
                data : filter,
                dataType : 'html',
                success : function(data) {
                    $('#gallery').html(data);
                    $('#blueimp-gallery').data('useBootstrapModal', false);
                },
                error : function(data) {
                    //alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
                },
                complete : function(data) {
                    stop_logo_fly();
                }
            });
        }
        
        function displayFirstWebsite(){
            //on n'affiche pas l'arc et la bar en opacite max car la transition est encore en cours
            
            //on remet tous les noms des sites en normal
            $(".websiteLi span").removeClass("selected");
            
            //on selectionne le site correspondant
            var websiteLiName = $(".websiteLi:first span");
            websiteLiName.addClass("selected");
            
            //on change les données
            var websiteLi = $(".websiteLi:first");
            
            //on selectionne la bar correspondante
            var bar = d3.select(".bar[index='"+websiteLi.attr('index')+"']");
            bar.attr("stroke", "black");
            $("#dataHeight").text(dataNumberToNice(websiteLi.attr("total")));
        }
        
        if(!chartHasBeenShowed){
            $("#bar-chart-limit:in-viewport").each(function() {
                chartHasBeenShowed = true;
                //barChart init
                init();
                displayFirstWebsite();
                get_heights_gallery_all();
            });
        }
        
    });
    
    function dataNumberToNice(number){
        
        var numberFloor = Math.floor(number);
        
        if(numberFloor===0){
            numberFloor = Math.floor(number*10)/10;
        }
        
        if(numberFloor===0){
            numberFloor = Math.floor(number*100)/100;
        }
        
        numberFloor = numberFloor.toString();
        var pattern = /(-?\d+)(\d{3})/;
        while (pattern.test(numberFloor))
            numberFloor = numberFloor.replace(pattern, "$1 $2");
        
        return numberFloor;
    }
    
})();