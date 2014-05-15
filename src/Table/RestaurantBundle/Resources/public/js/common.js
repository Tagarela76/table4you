function ActiveTable() {
    var that = this;

    this.cancelTableMove = function(tableId) {
        // Get Table Obj
        var tableObj = $("#activeTable_" + tableId);
        // Get Old Coordinates
        var leftPosition = tableObj.attr("leftPosition");
        var topPosition = tableObj.attr("topPosition");
        // Cancel moving 
        tableObj.css("left", leftPosition + "px");
        tableObj.css("top", topPosition + "px");
    }

    this.updateActiveTablePosition = function(activeTableId) {

        // Get new position
        var left = $("#activeTable_" + activeTableId).position().left;
        var top = $("#activeTable_" + activeTableId).position().top;
        var tableAngle = $("#activeTable_" + activeTableId).getRotateAngle();

        // Get new position
        $.ajax({
            url: Routing.generate('table_updateActiveTable'),
            data: {activeTableId: activeTableId, leftPosition: left,
                topPosition: top, tableAngle: tableAngle[0]},
            type: "POST",
            dataType: "html",
            success: function() {
                location.reload();
            }
        });
    }

    this.loadTableTypeContainer = function() {

        $.ajax({
            url: Routing.generate('table_loadTableTypeContainer'),
            type: "GET",
            dataType: "html",
            success: function(response) {
                $("#table-container").html(response);
            }
        });
    }

    this.loadActiveTable = function(activeTableId) {

        $.ajax({
            url: Routing.generate('table_loadActiveTable') + "/" + activeTableId,
            type: "GET",
            dataType: "html",
            success: function(response) {
                $("#table-container").html(response);
            }
        });
    }

    this.closeAddTablePopup = function() {
        // close modal window
        $('#tableNumberContainer').modal('hide');
    }

    this.initTableData = function(top, left, tableType) {
        $("#tableTop").val(top);
        $("#tableLeft").val(left);
        $("#tableType").val(tableType);
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

        $.ajax({
            url: Routing.generate('table_deleteActiveTable'),
            data: {activeTableId: activeTableId},
            type: "POST",
            dataType: "html",
            success: function() {
                location.reload();
            }
        });
    }
}

function TableMap() {

    this.rotateTables = function() {

        // Get Tables
        $.ajax({
            url: Routing.generate('table_getActiveTableList'),
            type: "GET",
            dataType: "json",
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    var activeTable = response[i];
                    console.log(activeTable);
                    // Rotate Tables
                    var startAngle = 0;
                    $(".active-table_" + activeTable.id).rotate({
                        angle: activeTable.angle,
                        bind:
                                {
                                    click: function() {
                                        startAngle += 90;
                                        $(this).rotate({animateTo: startAngle})
                                    }
                                }
                    });
                }
            }
        });
    }

    this.loadMapScheme = function(mapScheme) {
        $('#tableMapDroppable').css('background-image', 'url(' + mapScheme + ')');
        $('#tableMapDroppable').css('background-size', '500px 500px');
    }

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
            var selectTheFileLabel = $("#selectTheFileLabel").val();
            var rowContainer = "<div class='row-fluid add-file-map'>" +
                    "<span class='span12'><div class='list-text-floor-hall'>" + mapFileLabel + "</div>" +
                    "<div class='fileinput fileinput-new' data-provides='fileinput'>" +
                    "<div class='input-group'>" +
                    "<div class='form-control files-download' data-trigger='fileinput'>" +
                    "<i class='glyphicon glyphicon-file fileinput-exists'></i>" +
                    "<span class='fileinput-filename'></span>" +
                    "</div>" +
                    "<span class='input-group-addon btn btn-default btn-file'>" +
                    "<span class='fileinput-new'>" + selectTheFileLabel + "</span>" +
                    "<input type='file' name='mapFile[]' size='1'>" +
                    "</span>" +
                    "</div>" +
                    "</div><a href='#' onclick='page.common.removeFileField(this); return false;'>" +
                    "<img src='" + deleteRowIcon + "'>" +
                    "</a>" +
                    "</span>" +
                    "<span class='span4'><div class='list-text-floor-hall'>" + mapFloorLabel +
                    "</div><input type='text' name='mapFloor[]' size='2' class='valid-number'/>" +
                    "</span>" +
                    "<span class='span5'><div class='list-text-floor-hall'>" + mapHallLabel +
                    "</div><input type='text' name='mapHall[]' size='2' class='valid-number'/></span>" +
                    "</div>";

            $('#mapFieldsContainer').append(rowContainer);
            page.common.allowDigitsOnly("valid-number");
        }
    }

    this.removeRow = function(element) {
        $(element).closest("div").remove();
    }

    this.validateEditForm = function(el) {

        // Get parent form
        var form = $(el).closest('form');

        //reset errors
        $(".validationError").css("display", "none");
        $(".validationFileError").css("display", "none");
        form.find($('input[name="mapFloor"]')).removeClass("error-form");
        form.find($('input[name="mapFile"]')).removeClass("error-form");

        var isError = false;

        var mapFile = form.find($('input[name="mapFile"]')).val();
        if (mapFile != "") {
            // Check image format
            var mapFileArray = mapFile.split(".");
            var ext = mapFileArray[mapFileArray.length - 1];
            if (ext != "jpg" && ext != "jpeg" && ext != "JPEG" && ext != "png") {
                form.find($('input[name="mapFile"]')).addClass("error-form");
                $(".validationFileError").css("display", "block");
                isError = true;
            }
        }

        //check if people count field empty
        if (form.find($('input[name="mapFloor"]')).val() == "") {
            //display error
            $(".validationError").css("display", "block");
            form.find($('input[name="mapFloor"]')).addClass("error-form");
            isError = true;
        }
        if (!isError) {
            // submit form
            form.submit();
        }

    }

    this.validateAddForm = function() {
        //reset errors
        $(".validationError").css("display", "none");

        //check if floor field empty
        var isFloorEmptyError = false;
        $(":input[name='mapFloor[]']").each(function(i) {
            if ($(this).val() == "") {
                isFloorEmptyError = true;
                $(this).addClass("error-form");
            }
        });
        //check if file field empty
        var isFileEmptyError = false;
        var isFileIncorrectError = false;
        $(":input[name='mapFile[]']").each(function(i) {
            // Check image format
            var mapFileArray = $(this).val().split(".");
            var ext = mapFileArray[mapFileArray.length - 1];
            if (ext != "jpg" && ext != "jpeg" && ext != "JPEG" && ext != "png") {
                isFileIncorrectError = true;
            }
            if ($(this).val() == "") {
                isFileEmptyError = true;

            }
            if (isFileEmptyError || isFileIncorrectError) {
                $(this).addClass("error-form");
            }

        });

        //validate
        if (isFloorEmptyError || isFileEmptyError || isFileIncorrectError) {
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
                location.href = Routing.generate('table_viewCreateMap') + "/" + restaurantId;
            }
        });
    }
}

function TableType() {

    this.validateEditForm = function(el) {
        // Get parent form
        var form = $(el).closest('form');
        //reset errors
        $(".validationError").css("display", "none");
        form.find($('input[name="file"]')).removeClass("error-form");
        form.find($('input[name="peopleCount"]')).removeClass("error-form");

        var isError = false;
        // Check image format
        var file = form.find($('input[name="file"]')).val();
        if (file != "") {
            var fileArray = file.split(".");
            var ext = fileArray[fileArray.length - 1];
            if (ext != "jpg" && ext != "jpeg" && ext != "JPEG" && ext != "png") {
                form.find($('input[name="file"]')).addClass("error-form");
                $(".validationError").css("display", "block");
                isError = true;
            }
        }
        //check if people count field empty
        if (form.find($('input[name="peopleCount"]')).val() == "") {
            //display error
            $(".validationError").css("display", "block");
            form.find($('input[name="peopleCount"]')).addClass("error-form");
            isError = true;
        }
        if (!isError) {
            // submit form
            form.submit();
        }

    }

    this.validateAddForm = function() {
        //reset errors
        $(".validationError").css("display", "none");

        //check if people count field empty
        var isPeopleCountEmptyError = false;
        $(":input[name='peopleCount[]']").each(function(i) {
            if ($(this).val() == "") {
                isPeopleCountEmptyError = true;
                $(this).addClass("error-form");
            }
        });
        //check if file field empty
        var isFileEmptyError = false;
        $(":input[name='file[]']").each(function(i) {
            if ($(this).val() == "") {
                console.log();
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

        // rebuilt map
        $("#restaurant-map-body_" + restaurantId).html("<div class=\"restaurant-map\"  id=\"restaurant-map_" + restaurantId + "\"></div>");
        var mapTitle = $("#map-title").val();
        $('#restaurantMap_' + restaurantId + ' h3').html(mapTitle);

        var latitude = $("#latitude_" + restaurantId).val();
        var longitude = $("#longitude_" + restaurantId).val();
        var restaurantName = $("#restaurantName_" + restaurantId).val();
        var reserveButton = $("#reserveButton_" + restaurantId).val();

        var restaurantContent = "<div><b>" + restaurantName + "</b></div>" + reserveButton;

        var map = L.map('restaurant-map_' + restaurantId, {
            center: new L.LatLng(latitude, longitude),
            zoom: 18
        });
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '',
            maxZoom: 18
        }).addTo(map);
        /*L.tileLayer('http://{s}.tile.cloudmade.com/{key}/{styleId}/256/{z}/{x}/{y}.png', {
            key: '8EE2A50541944FB9BCEDDED5165F09D9',
            styleId: 997,
            attribution: '',
            maxZoom: 18
        }).addTo(map);*/

        var marker = L.marker([latitude, longitude]).addTo(map);
        marker.bindPopup(restaurantContent);

        // Load full map (fix bug with leaflet map size)
        $("#restaurantMap_" + restaurantId).on('show.bs.modal', function() {
            setTimeout(function() {
                map.invalidateSize();
            }, 10);
        });
        $('html, body').animate({scrollTop: 0}, 'fast');
    }
}

function TableOrder() {

    var that = this;

    this.loadMapSchemeAdmin = function(mapScheme) {
        $('#table-map-image-container').css('background-image', 'url(' + mapScheme + ')');
        $('#table-map-image-container').css('background-size', '500px 500px');
    }
    
    this.refreshReserveWindow = function() {
        $('#activeTableOrder4AdminForm_userPhone').mask('+79999999999');

        // Bind submit on reserve
        that.bindSubmitOnReserve();

        // Init calendar
        that.initCalendar();
    }

    /** Admin usage **/
    this.bindSubmitOnReserve = function() {
        $('#table-order-form').submit(function() {
            $(this).ajaxSubmit({
                type: 'post',
                beforeSubmit: function() {
                    $("#reservePreloader").show();
                },
                success: function(response) {
                    $("#reservePreloader").hide();
                    // return response
                    $("#table-order-form-container").html(response);
                    that.initCalendar();
                }
            });
            return false;
        });
    }

    this.enterTableNumberByHand = function(el) {
        // Get entered table number
        var tableNumber = $(el).val();
        // get map id
        var mapId = $("#mapId").val()
        $.ajax({
            url: Routing.generate('table_enterTableNumberByHand'),
            data: {tableNumber: tableNumber, mapId: mapId},
            type: "GET",
            dataType: "html",
            success: function(response) {
                // response - table id, if 0 - nothing found
                if (response == "") {
                    // Reset
                    $("#activeTableOrderForm_activeTable").val("");
                    $(".active-table-img").removeClass("active-table-selected");
                    //  alert("Nothing Found!");
                } else {
                    that.selectActiveTable(response);
                }
            }
        });
    }

    this.initCalendar = function() {
        $.datepicker.setDefaults($.datepicker.regional['ru']);
        $('.reserveDate').datepicker({
            dateFormat: 'yy-mm-dd',
            altField: "#activeTableOrder4AdminForm_reserveDate",
            altFormat: "yy-mm-dd",
            minDate: '0',
            maxDate: '+1m'
        })
    }

    this.refreshOrderList = function(tableId) {
        $.ajax({
            url: Routing.generate('table_viewActiveTableOrderList'),
            data: {tableId: tableId, acceptReserve: 1},
            type: "GET",
            dataType: "html",
            success: function(response) {
                $('#activeTableOrderContainer').html(response);
            }
        });
    }

    this.refreshBookedTableListInClientDashboard = function(restaurantId, filterDate) {
        // get map id
        var mapId = $("#mapId_" + restaurantId).val();
        // Get time
        var filterTimeHour = $("#activeTableOrderForm_reserveTime_hour").val();
        var filterTimeMinute = $("#activeTableOrderForm_reserveTime_minute").val();

        $.ajax({
            url: Routing.generate('table_refreshBookedTableListInClientDashboard'),
            data: {mapId: mapId, filterDate: filterDate,
                filterTimeHour: filterTimeHour, filterTimeMinute: filterTimeMinute},
            type: "GET",
            dataType: "html",
            success: function(responce) {
                $('#table-map-image-container_' + restaurantId).html(responce);
                // Reset Selected Table
                $("#activeTableOrderForm_activeTable").val("");
                $("#activeTableOrderForm_tableNumber").val("");
            }
        });
    }

    this.viewFilter = function() {

        $.ajax({
            url: Routing.generate('table_viewActiveTableOrderFilter'),
            type: "GET",
            dataType: "html",
            success: function(response) {
                $("#activeTableOrderContainer").html(response);
            }
        });
    }

    this.refreshBookedTableListInAdminDashboard = function(filterDate) {
        // get map id
        var mapId = $("#mapId").val();

        // Get time
        var filterTime = $("#tableOrderTimepicker").val();
        // change table map
        $.ajax({
            url: Routing.generate('table_refreshBookedTableListInAdminDashboard'),
            data: {mapId: mapId, filterDate: filterDate, filterTime: filterTime},
            type: "GET",
            dataType: "html",
            success: function(responce) {
                $('#table-map-image-container').html(responce);
            }
        });
    }

    // Seems like unused!!!! TO DO : Check
    this.refreshBookedTableList = function() {
        // get restaurant id
        var restaurantId = $("#restaurantId").val();
        // change table map
        $.ajax({
            url: Routing.generate('table_refreshBookedTableList'),
            data: {restaurantId: restaurantId},
            type: "GET",
            dataType: "html",
            success: function(responce) {
                $('#restaurantTableMapContainer_' + restaurantId).html(responce);
            }
        });
    }

    this.selectActiveTable = function(activeTableId) {

        // Get Table Number
        var tableNumber = $("#activeTable_" + activeTableId).attr("tableNumber");
        // Set Table Number
        $("#activeTableOrderForm_tableNumber").val(tableNumber);
        // Set Active Table
        $("#activeTableOrderForm_activeTable").val(activeTableId);

        // Update styles
        $(".active-table-img").removeClass("active-table-selected");
        $("#activeTable_" + activeTableId).addClass("active-table-selected");

    }

    this.loadMapScheme = function(restaurantId, mapScheme) { 
        $('#table-map-image-container_' + restaurantId).css('background-image', 'url(' + mapScheme + ')');
        $('#table-map-image-container_' + restaurantId).css('background-size', '400px 400px');
    }

    this.loadMap = function(obj, tableMapId) {
        // get restaurant id
        var restaurantId = $(obj).attr("restaurantId");
        // remove active class from all
        $(".halls").removeClass('active');
        // set active for current obj
        $(obj).addClass('active');

        // refresh map
        $.ajax({
            url: Routing.generate('table_loadMapPicture'),
            data: {tableMapId: tableMapId},
            type: "GET",
            dataType: "html",
            success: function(responce) {
                // update map scheme
                that.loadMapScheme(restaurantId, responce);
            }
        });
        // refresh table list
        $.ajax({
            url: Routing.generate('table_viewActiveTableList'),
            data: {restaurantId: restaurantId, tableMapId: tableMapId},
            type: "GET",
            dataType: "html",
            success: function(response) { 
                $('#table-map-image-container_' + restaurantId).html(response);
            }
        });
    }

    this.selectRestaurantMap = function(obj, floor) {
        // remove active class from all
        $(".floors").removeClass('active');
        // set active for current obj
        $(obj).addClass('active');

        // get restaurant id
        var restaurantId = $(obj).attr("restaurantId"); 
        // change table map
        $.ajax({
            url: Routing.generate('table_refreshTableMap'),
            data: {restaurantId: restaurantId, floor: floor},
            type: "GET",
            dataType: "html",
            success: function(responce) {
                $('#restaurantTableMapContainer_' + restaurantId).html(responce);
            }
        });
    }

    this.initTableData = function(activeTableId) {
        // Set active table id
        $("#activeTableOrder4AdminForm_activeTable").val(activeTableId);
        $("#activeTableId").val(activeTableId);
        // Get table number
        var tableNumber = $("#tableContainer_" + activeTableId).attr('tableNumber');
        $("#activeTableOrder4AdminForm_tableNumber").val(tableNumber);
    }

    this.initFancyTimeBox = function() {

        /*var timeParams = {
         changedEl: "#table-order-form select",
         visRows: 8,
         scrollArrows: true
         }
         cuSel(timeParams);*/
    }

    this.deleteActiveTableOrder = function(tableOrderId) {
        // buttons order will be OK, Cancel
        alertify.set({ buttonReverse: true }); // true, false
        // custom OK and Cancel label
        // default: OK, Cancel
        var confirmDeleteOrderOK = $("#confirmDeleteOrderOK").val();
        var confirmDeleteOrderCancel = $("#confirmDeleteOrderCancel").val();
        var confirmDeleteOrderMessage = $("#confirmDeleteOrderMessage").val();
        alertify.set({ labels: {
            ok     : confirmDeleteOrderOK,
            cancel : confirmDeleteOrderCancel
        } });
        // confirm dialog
        alertify.confirm(confirmDeleteOrderMessage, function(e) {
            if (e) {
                // user clicked "ok"
                var acceptReserve = $("#acceptReserve").val();
                $.ajax({
                    url: Routing.generate('table_deleteActiveTableOrder'),
                    data: {tableOrderId: tableOrderId, acceptReserve: acceptReserve},
                    type: "POST",
                    dataType: "html",
                    beforeSubmit: function() {
                       $("#deleteOrderPreloader_" + tableOrderId).show();
                    },
                    success: function(responce) {
                        $("#deleteOrderPreloader_" + tableOrderId).hide();
                        $('#activeTableOrderContainer').html(responce);
                    }
                });
            } else {
                return false;
            }
        });        
    }
    
    this.loadOrderList = function(tableId, acceptReserve) {

        // Remove active class for all
        $(".active-table-img").removeClass("active-table-selected");
        // Select Table
        $("#tableContainer_" + tableId).addClass("active-table-selected");
        $.ajax({
            url: Routing.generate('table_viewActiveTableOrderList'),
            data: {tableId: tableId, acceptReserve: acceptReserve},
            type: "GET",
            dataType: "html",
            success: function(response) {
                $('#activeTableOrderContainer').html(response);
            }
        });
    }

    this.refreshRestaurantList = function() {
        var restaurantId = $("#restaurantId").val();
        location.href = Routing.generate('table_viewTableOrderList') + "/" + restaurantId;
    }

    // old Methods


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

        /*var timeParams = {
         changedEl: ".table-order-form select",
         visRows: 8,
         scrollArrows: true
         }
         cuSel(timeParams);*/
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

    var that = this;

    this.allowDigitsOnly = function(elClass) {
        $('input.' + elClass).bind('keypress', function(e) {
            return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) ? false : true;
        });
    }

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
                $('.close').click(function() {
                    $('#restaurantMap_' + restaurantId).css("width", "816px");
                    $('.modal').css("margin-left", "-408px");
                });
            }
        });
    }

    this.addNewFileField = function() {
        //should be no more than 10
        var fileContaunerCount = $(":input[name='peopleCount[]']").length;
        if (fileContaunerCount != 10) {
            var peopleCountLabel = $("#peopleCountLabel").val();
            var deleteFileIcon = $("#deleteFileIcon").val();
            var selectTheFileLabel = $("#selectTheFileLabel").val();
            var numberOfPlaces = $("#numberOfPlaces").val();
            var fileContainer =
                    "<div class='row-fluid'>" +
                    "<span class='span12 add-more-file'>" +
                    "<div class='number-tables'>" + peopleCountLabel +
                    "<input type='text' name='peopleCount[]' size='2' class='valid-number'>" +
                    " " + numberOfPlaces + "</div>" +
                    "<div class='fileinput fileinput-new' data-provides='fileinput'>" +
                    "<div class='input-group'>" +
                    "<div class='form-control files-download' data-trigger='fileinput'>" +
                    "<i class='glyphicon glyphicon-file fileinput-exists'></i> " +
                    "<span class='fileinput-filename'></span></div>" +
                    "<span class='input-group-addon btn btn-default btn-file'>" +
                    "<span class='fileinput-new'>" + selectTheFileLabel + "</span>" +
                    "<input type='file' name='file[]' size='1'></span>" +
                    "</div>" +
                    "</span></div>" +
                    "<a href='#' onclick='page.common.removeFileField(this); return false;' id='delete-file-customization'> " +
                    "<img alt='Delete' src='" + deleteFileIcon + "'>" +
                    "</a></div>";

            $('#fileFieldsContainer').append(fileContainer);
            that.allowDigitsOnly("valid-number");
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

