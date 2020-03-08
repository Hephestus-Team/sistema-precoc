$(function(){ 
    getStatus();
});

function getStatus(){
    $.ajax({
        type:"POST",
        url : "../../app/controllers/StatusController.php",  
        success: function(valor){printarPagina(valor)},
    });
}

function printarPagina(valor){

    const open = "../views/open.php";
    const close = "../views/close.php";

    if(valor == "true"){
        $.ajax({
            type:"POST",
            url : open,  
            success: function(valor){$('body').html(valor)},
        });
    }
    else{
        $.ajax({
            type:"POST",
            url : close,  
            success: function(valor){$('body').html(valor)},
        });
    }
}