
function ActiveTable() {

    this.initTableData = function(top, left, tableType) {
        $("#tableTop").val(top);
        $("#tableLeft").val(left);
        $("#tableType").val(tableType);
    }
    
    this.loadActiveTables = function() {
        var mapId = $("#mapId").val();
        // get Active Tables
        $.ajax({
            url: Routing.generate('table_loadActiveTabless'),
            data: {mapId: mapId},
            type: "GET",
            dataType: "json",
            success: function(response) { 
                for (var i = 0; i < response.length; i++) {
                    var imgContainer = "";
                    var activeTable = response[i];
                    var left = activeTable.left + $("#tableMapDroppable").position().left;
                    var top = activeTable.top + $("#tableMapDroppable").position().top;
                    imgContainer += "<img tabletypeid='" + activeTable.tableTypeId + "' " +
                            "class='active-table-draggable' " +
                            "src='" + activeTable.src + "' " +
                            "style='position: absolute; " +
                            "left: " + left + "px; "+
                            "top: " + top + "px;'>";

                    $('#activeTablesContainer').append(imgContainer);
                }
            }  
	}); 
    }
    
    this.deleteActiveTable = function(activeTableId) {

       /* $.ajax({
            url: Routing.generate('table_deleteTableMap'),
            data: {tableMapId: tableMapId, restaurantId: restaurantId},
            type: "POST",
            dataType: "html",
            success: function() {
                location.reload();
            }
        });*/
    }
    
    this.validateAddForm = function() {
        //reset errors
        $(".validationError").css("display", "none");
        
        //validate
        if ($(":input[name='tableNumber']").val() == "") {
            //display error
           $(".validationError").css("display", "block");
        } else {
            // submit form
            $("#add-table-on-the-map").submit(); 
        }
        
    }
    
    this.deleteActiveTable = function(activeTableId) {

       /* $.ajax({
            url: Routing.generate('table_deleteTableMap'),
            data: {tableMapId: tableMapId, restaurantId: restaurantId},
            type: "POST",
            dataType: "html",
            success: function() {
                location.reload();
            }
        });*/
    }
}

function TableMap() {
    this.refreshRestaurantList = function() {
        var restaurantId = $("#restaurantId").val();
        location.href = Routing.generate('table_viewCreateMap') + "/" + restaurantId;
    }
    
    this.addNewRow = function() {
        //should be no more than 10
        var containerCount = $(":input[name='mapFloor[]']").length; 
        if (containerCount != 10) {
            var deleteRowIcon = $("#deleteRowIcon").val();
            var mapFileLabel = $("#mapFileLabel").val();
            var mapFloorLabel = $("#mapFloorLabel").val();
            var mapHallLabel = $("#mapHallLabel").val();
            var rowContainer = "<div class='row-fluid add_file_map'>" +
                               "<span class='span12'><div class='list_text'>" + mapFileLabel +
                               "</div><div id='customization_name'></div><div class='customization'><div>Выбрать файл</div><input type='file' name='mapFile[]' size='30' id='customization_file'/></div>"+
                               "</span>" +
                               "<span class='span4'><div class='list_text'>" + mapFloorLabel +
                               "</div><input type='text' name='mapFloor[]' size='2' />" +
                               "</span>" +
                               "<span class='span5'><div class='list_text'>" + mapHallLabel +
                               "</div><input type='text' name='mapHall[]' size='2'/></span>" +
                               "<span class='span2'><a href='#' onclick='page.common.removeFileField(this); return false;'> " +
                               "<img alt='Delete' src='" + deleteRowIcon + "'>" +
                               "</a>" +
                               "</span>" +
                               "</div>";

            $('#mapFieldsContainer').append(rowContainer);
        }
    }

    this.removeRow = function(element) {
        $(element).closest("div").remove();
    }
    
    this.validateEditForm = function(el) {
        //reset errors
        $(".validationError").css("display", "none");
        // Get parent form
        var form = $(el).closest('form');
        //check if people count field empty
        if (form.find($('input[name="mapFloor"]')).val() == "") {
            //display error
            $(".validationError").css("display", "block");
            form.find($('input[name="mapFloor"]')).addClass("error-form");
        } else {
            // submit form
            form.submit(); 
        }
        
    }
    
    this.validateAddForm = function() {
        //reset errors
        $(".validationError").css("display", "none");
        
        //check if floor field empty
        var isFloorEmptyError = false;
        $(":input[name='mapFloor[]']").each(function(i){
            if($(this).val() == "") {
                isFloorEmptyError = true;
                $(this).addClass("error-form");
            }
        });
        //check if file field empty
        var isFileEmptyError = false;
        $(":input[name='mapFile[]']").each(function(i){
            if($(this).val() == "") {
                isFileEmptyError = true;
                $(this).addClass("error-form");
            }
        });

        //validate
        if (isFloorEmptyError || isFileEmptyError) {
            //display error
           $(".validationError").css("display", "block");
        } else {
            // submit form
            $("#table-map-form").submit(); 
        }
        
    }
    
    this.deleteTableMap = function(tableMapId) {
        var restaurantId = $("#restaurantId").val();
        $.ajax({
            url: Routing.generate('table_deleteTableMap'),
            data: {tableMapId: tableMapId, restaurantId: restaurantId},
            type: "POST",
            dataType: "html",
            success: function() {
                location.reload();
            }
        });
    }
}

function TableType() {
    
    this.validateEditForm = function(el) {
        //reset errors
        $(".validationError").css("display", "none");
        // Get parent form
        var form = $(el).closest('form');
        //check if people count field empty
        if (form.find($('input[name="peopleCount"]')).val() == "") {
            //display error
            $(".validationError").css("display", "block");
            form.find($('input[name="peopleCount"]')).addClass("error-form");
        } else {
            // submit form
            form.submit(); 
        }
        
    }
    
    this.validateAddForm = function() {
        //reset errors
        $(".validationError").css("display", "none");
        
        //check if people count field empty
        var isPeopleCountEmptyError = false;
        $(":input[name='peopleCount[]']").each(function(i){
            if($(this).val() == "") {
                isPeopleCountEmptyError = true;
                $(this).addClass("error-form");
            }
        });
        //check if file field empty
        var isFileEmptyError = false;
        $(":input[name='file[]']").each(function(i){
            if($(this).val() == "") {
                isFileEmptyError = true;
                $(this).addClass("error-form");
            }
        });

        //validate
        if (isPeopleCountEmptyError || isFileEmptyError) {
            //display error
           $(".validationError").css("display", "block");
        } else {
            // submit form
            $("#table-type-form").submit(); 
        }
        
    }
    
    this.deleteTableType = function(tableTypeId) {
        $.ajax({
            url: Routing.generate('table_deleteTableType'),
            data: {tableTypeId: tableTypeId},
            type: "POST",
            dataType: "html",
            success: function(responce) {
                $('#tableTypeContainer').html(responce);
            }
        });
    }
}

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

    this.loadOrderList = function(tableId) {
        $.ajax({
            url: Routing.generate('table_viewActiveTableOrderList'),
            data: {tableId: tableId},
            type: "GET",
            dataType: "html",
            success: function(response) { 
                $('#activeTableOrderContainer').html(response);
            }  
	}); 
    }
    
    this.loadActiveTables = function() {
        var mapId = $("#mapId").val();
        // get Active Tables
        $.ajax({
            url: Routing.generate('table_loadActiveTabless'),
            data: {mapId: mapId},
            type: "GET",
            dataType: "json",
            success: function(response) { 
                for (var i = 0; i < response.length; i++) {
                    var imgContainer = "";
                    var activeTable = response[i];
                    var left = activeTable.left + $("#tableMapDroppable").position().left;
                    var top = activeTable.top + $("#tableMapDroppable").position().top;
                    imgContainer += "<img tabletypeid='" + activeTable.tableTypeId + "' " +
                            "class='' " +
                            "src='" + activeTable.src + "' " +
                            "style='position: absolute; " +
                            "left: " + left + "px; "+
                            "top: " + top + "px;' " +
                            "onclick='page.tableOrder.loadOrderList(" + activeTable.id + ");'>";

                    $('#activeTablesContainer').append(imgContainer);
                }
            }  
	}); 
    }
    
    this.refreshRestaurantList = function() {
        var restaurantId = $("#restaurantId").val();
        location.href = Routing.generate('table_viewTableOrderList') + "/" + restaurantId;
    }
    
    // old Methods
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
    
    this.addNewFileField = function() {
        //should be no more than 10
        var fileContaunerCount = $(":input[name='peopleCount[]']").length; 
        if (fileContaunerCount != 10) {
            var peopleCountLabel = $("#peopleCountLabel").val();
            var deleteFileIcon = $("#deleteFileIcon").val();
            var fileContainer = "<div class='row-fluid'>" +
                    "<span class='span12 add_more_file'><div class='number_tables'>" + peopleCountLabel +
                    "<input type='text' name='peopleCount[]' size='2'>мест(а)</div>" +
                    "<div id='customization_name'></div>"+
                    "<div class='customization'><div>Выбрать файл</div><input type='file' name='file[]' size='30' id='customization_file'></div>" +
                    "<a href='#' onclick='page.common.removeFileField(this); return false;' id='delete_file_customization'> " +
                    "<img alt='Delete' src='" + deleteFileIcon + "'>" +
                    "</a></span></div>";

            $('#fileFieldsContainer').append(fileContainer);
        }
    }

    this.removeFileField = function(element) {
        $(element).closest("div").remove();
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
    this.tableType = new TableType();
    this.tableMap = new TableMap();
    this.activeTable = new ActiveTable();
    this.t = new ActiveTable();
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
