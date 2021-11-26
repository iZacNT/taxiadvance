$("#stock-type").on("click", 'input[type="radio"]:checked',function(){
    if($(this).val() == 1 ){
        $("#stock-invoice").attr('disabled', false);
        $("#autocompleteCarRepair").attr('disabled', true);
    }else{
        $("#stock-invoice").attr('disabled', true);
        $("#autocompleteCarRepair").attr('disabled', false);
    }
});