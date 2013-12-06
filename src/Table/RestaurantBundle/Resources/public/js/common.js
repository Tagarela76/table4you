function TableOrder() {
    
    this.selectRestaurantFloor = function(obj, floor) {
        // remove active class from all
        $(".floors").removeClass('active'); 
        $('#tableOrderForm_floor').val(floor);
        $(obj).addClass('active');                            
    }
    
    this.view = function(restaurantId) {
        var reserveTitle = $("#reserveTitle").val();
        $.ajax({
            url: Routing.generate('table_order_reserve') + "/" + restaurantId,
            data: {fromMap: 1},
            type: "POST",
            dataType: "html",
            success: function(responce) {
                $('.modal-header h3').html(reserveTitle);
                $('#restaurant-map').html(responce);
            }  
	});  
    }
    
    this.initFancySelectBox = function() {

        var timeParams = {
            changedEl: ".table-order-form select",
            visRows: 8,
            scrollArrows: true
        }
        cuSel(timeParams);
    }
}

function OrderHistory() {
    
    this.filterOrderHistory = function() {
	var filterDate = $("#filterDate").val();
	var searchStr = $("#searchStr").val();
        document.location.href = Routing.generate('table_order_history') + "?filterDate=" + filterDate + "&searchStr=" + searchStr;                            
    }
}

function Rating() {
    
    this.initRating = function() { 
	$('.rating').rating({
            callback: function(value, link){ 
                var restaurantId = $(this).attr('name');
                $.ajax({
                    url: Routing.generate('table_update_restaurant_rating'),
                    data: {restaurantId: restaurantId, value: value, objId: restaurantId},
                    type: "POST",
                    dataType: "html",
                    success: function(responce) {
                        location.reload();
                        //$('.restaurant-rating-'+restaurantId).html(responce);
                    }
                });
            }
        });
        $(".rating").removeClass('rating'); 
    }
}

function InfiniteLoad() {
    
    this.initLoading = function(entity) {
        
      //  var loader = "{{ asset('bundles/tablemain/infinite-ajax-scroll/images/loader.gif') }}"; 
        
        jQuery.ias({
            container : '.'+entity+'List-container',
            item: '.'+entity+'-container',
            pagination: '.navigation',
            next: '.next a',
            loader: '<img src="bundles/tablemain/infinite-ajax-scroll/images/loader.gif" />',
            history: false,
         //   triggerPageThreshold: 2,
            onRenderComplete: function(items) {
                // init rating
                page.rating.initRating();    
            }
        }); 
    }
}

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
                        // init rating
                        page.rating.initRating();
                        // init infinite ajax
                        page.infiniteLoad.initLoading('restaurant');
		}  
	});      
        
    }
}

function NewsFilter() {
 
    this.refreshNewsList = function() {
	// get city
	var searchCity = $("#searchRestaurantsCity_main").val();
	
	$.ajax({
		url: Routing.generate('table_all_news')+"/"+searchCity,
		type: "POST",
		dataType: "html",
		success: function(responce) {
		        $('#newsList_main').html(responce);
		}  
	});      
        
    }
}

function Common() {
 
    this.closeModalWindow = function(element) {
        $('.modal-backdrop fade in').remove();
    }
}

function Page() {
    this.tableOrder = new TableOrder();
    this.orderHistory = new OrderHistory();
    this.rating = new Rating();
    this.infiniteLoad = new InfiniteLoad();
    this.restaurantFilter = new RestaurantFilter();
    this.common = new Common();
    this.newsFilter = new NewsFilter();
}

//	global page object
var page;

$(function() {
    //	ini global object
    page = new Page();
});
