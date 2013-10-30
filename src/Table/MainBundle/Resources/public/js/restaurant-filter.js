function RestaurantFilter() {
 
    this.refreshRestaurantList = function() {
        // get search string
	var restaurantSearchStr = $("#searchRestaurantsStr_main").val();

	// get category list
	var restaurantCategoryList = new Array();
	$('.restaurantCategoryList_main :selected').each(function(i, selected){ 
		restaurantCategoryList[i] = $(selected).val(); 
	});

	// get kitchen list
	var restaurantKitchenList = new Array();
	$('.restaurantKitchenList_main :selected').each(function(i, selected){ 
		restaurantKitchenList[i] = $(selected).val(); 
	});

	// get city
	var searchCity = $("#searchRestaurantsCity_main").val();

	$.ajax({
		url: Routing.generate('table_main_refresh_restaurant_list'),
		data: {restaurantCategoryList: restaurantCategoryList, restaurantKitchenList: restaurantKitchenList, restaurantSearchStr: restaurantSearchStr, searchCity:searchCity},
		type: "POST",
		dataType: "html",
		success: function(responce) {
			//location.reload();
		        $('#restaurantList_main').html(responce);
		}  
	});                       
    }
}

//global object
var restaurantFilter;

$(function() {
    //	ini global object
    restaurantFilter = new RestaurantFilter();
});
