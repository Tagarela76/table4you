function TableOrder() {
    
    this.selectRestaurantFloor = function(obj, floor) {
        // remove active class from all
        $(".floors").removeClass('active'); 
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