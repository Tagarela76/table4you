function RestaurantMap() {

    this.openMap = function(restaurantId) {
        
        // change modal window width and left margin
       // $("#restaurantMap_" + restaurantId).css("width", "816px");
        
        // rebuilt map
        $("#restaurant-map-body_" + restaurantId).html("<div class=\"restaurant-map\"  id=\"restaurant-map_"+restaurantId+"\"></div>");
        var mapTitle = $("#map-title").val();
        $('.modal-header h3').html(mapTitle);
        var mapIcon = $("#map-icon").val();
        var restaurantIcon = L.icon({
            iconUrl: mapIcon,
            iconSize: [39, 41], // size of the icon
            iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 62], // the same for the shadow
            popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        });

        var latitude = $("#latitude_" + restaurantId).val();
        var longitude = $("#longitude_" + restaurantId).val();
        var restaurantName = $("#restaurantName_" + restaurantId).val();
        var reserveButton = $("#reserveButton_" + restaurantId).val();

        var restaurantContent = "<div><b>" + restaurantName + "</b></div>" + reserveButton;

        var map = L.map('restaurant-map_' + restaurantId, {
            center: new L.LatLng(latitude, longitude),
            zoom: 17
        });
        L.tileLayer('http://{s}.tile.cloudmade.com/{key}/{styleId}/256/{z}/{x}/{y}.png', {
            key: 'BC9A493B41014CAABB98F0471D759707',
            styleId: 997,
            attribution: '',
            maxZoom: 18
        }).addTo(map);

        var marker = L.marker([latitude, longitude], {icon: restaurantIcon}).addTo(map);
        marker.bindPopup(restaurantContent);

        // Load full map (fix bug with leaflet map size)
        $("#restaurantMap_" + restaurantId).on('show.bs.modal', function() {
            setTimeout(function() {
                map.invalidateSize();
            }, 10);
        });
    }
}

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
                $('.restaurant-map-body').html(responce);
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
            callback: function(value, link) {
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
        $(window).unbind();
        jQuery.ias({
            container: '.' + entity + 'List-container',
            item: '.' + entity + '-container',
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

    this.selectCity4Search = function() { 
        var city = $("#select-city option:selected").val();
        $("#searchRestaurantsCity_main").val(city); 
    }
    this.refreshRestaurantList = function() {
        // get search string
        var restaurantSearchStr = $("#searchRestaurantsStr_main").val();

        // get category list
        var restaurantCategoryList = new Array();
        $('.restaurantCategoryList_main :selected').each(function(i, selected) {
            restaurantCategoryList[i] = $(selected).val();
        });

        // get kitchen list
        var restaurantKitchenList = new Array();
        $('.restaurantKitchenList_main :selected').each(function(i, selected) {
            restaurantKitchenList[i] = $(selected).val();
        });

        // get city
        var searchCity = $("#searchRestaurantsCity_main").val();

        $.ajax({
            url: Routing.generate('table_main_homepage'),
            data: {restaurantCategoryList: restaurantCategoryList, restaurantKitchenList: restaurantKitchenList, restaurantSearchStr: restaurantSearchStr, searchCity: searchCity, filter: 1},
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
            url: Routing.generate('table_all_news') + "/" + searchCity,
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
    
    this.viewAuthPage = function(restaurantId) {      
        var reserveTitle = $("#reserveTitle_" + restaurantId).val();
        $.ajax({
            url: Routing.generate('fos_user_security_login'),
            type: "POST",
            dataType: "html",
            success: function(responce) {
                $('.modal-header h3').html(reserveTitle);
                $('.modal').css("margin-left", "-188px");
                $('#restaurantMap_' + restaurantId).css("width", "345px");
                $('.restaurant-map-body').html(responce);
            }
        });
    }
}

function UserProfile() {

    this.validateUserEmail = function(path) {
        $.ajax({
            url: Routing.generate('table_main_validate_user_email') + "/" + path,
            type: "GET",
            dataType: "html",
            success: function(responce) {
                if (responce == "false") {// user should fill email
                    location.href = Routing.generate('fos_user_profile_edit');
                }                
            }
        });
    }
}

function Page() {
    this.restaurantMap = new RestaurantMap();
    this.tableOrder = new TableOrder();
    this.orderHistory = new OrderHistory();
    this.rating = new Rating();
    this.infiniteLoad = new InfiniteLoad();
    this.restaurantFilter = new RestaurantFilter();
    this.common = new Common();
    this.newsFilter = new NewsFilter();
    this.userProfile = new UserProfile();
}

//global page object
var page;
//for restaurant page
var restaurantPage;
$(function() {
    //	ini global object
    page = new Page();
    restaurantPage = new Page();
});
