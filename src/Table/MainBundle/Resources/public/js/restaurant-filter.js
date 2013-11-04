function RestaurantFilter() {
 
    this.selectCity4Search = function(obj) {
	$("#searchRestaurantsCity_main").val($(obj).attr("city") );
	// remove active class from all
        $(".cityList").removeClass('active'); 
	// add class active for current obj
        $(obj).addClass('active');   
    }
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
		url: Routing.generate('table_main_homepage'),
		data: {restaurantCategoryList: restaurantCategoryList, restaurantKitchenList: restaurantKitchenList, restaurantSearchStr: restaurantSearchStr, searchCity:searchCity, filter: 1},
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
