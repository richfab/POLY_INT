<input type="text" id="data_input" value='{"department_id" : 2, "school_id" : [1,2], "motive_id" : 1, "city_id" : 6, "country_id" : "MX", "dateMin" : "2013-03-01 00:00:00", "dateMax" : "2015-03-01 00:00:00"}'>
<a onclick="get_map({});"> GET MAP INIT </a>
<a onclick="get_map({motive_id : 2, school_id : [1,2]});"> GET MAP </a>
<a onclick="get_experiences({});"> GET LIST </a>
<div id="experience_list"></div>