$("#dateToChange").on("change", function(){
    let date =  new Date($(this).val())/1000;
    if (isNaN(date)){
        date = "";
    }
    $('.changeDate').val(date);
});