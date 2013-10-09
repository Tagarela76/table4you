function TableOrder() {
    
    this.selectRestaurantFloor = function(obj, floor) {
        $('#tableOrderForm_floor').val(floor);
        $(obj).addClass('active');                            
    }
}

function Page() {
    this.tableOrder = new TableOrder();
}

//	global page object
var page;

$(function() {
    //	ini global object
    page = new Page();
});