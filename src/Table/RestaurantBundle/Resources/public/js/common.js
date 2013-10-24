function TableOrder() {
    
    this.selectRestaurantFloor = function(obj, floor) {
        // remove active class from all
        $(".floors").removeClass('active'); 
        $('#tableOrderForm_floor').val(floor);
        $(obj).addClass('active');                            
    }
}

function OrderHistory() {
    
    this.filterOrderHistory = function() {
	var filterDate = $("#filterDate").val();
	var searchStr = $("#searchStr").val();
        document.location.href = Routing.generate('table_order_history') + "?filterDate=" + filterDate + "&searchStr=" + searchStr;                            
    }
}

function Page() {
    this.tableOrder = new TableOrder();
    this.orderHistory = new OrderHistory();
}

//	global page object
var page;

$(function() {
    //	ini global object
    page = new Page();
});
