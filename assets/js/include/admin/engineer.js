
$(function(){
    $("#switch6").change(function() {
        if(this.checked) {
            $('.vendor_cls').show();
        }else{
            $('.vendor_cls').hide();
        }
    });
});