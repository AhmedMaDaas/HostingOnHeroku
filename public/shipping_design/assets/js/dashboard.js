$(document).ready(function(){
    $(document).on('click', '.customer-name', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $(".customers-modal#" + id).modal('show');
    });
    $(document).on('click', '.store-name', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $("#" + id + ".stores-modal").modal('show');
    });
    $(document).on('click', '.view-order', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $(".order-modal#" + id).modal('show');
    });
    $(document).on('click', '.edit-order', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $(".edit-cost-modal#" + id).modal('show');
    });
    $(document).on('click', '.order-id', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $(".order-fast-modal#" + id).modal('show');
    });
});