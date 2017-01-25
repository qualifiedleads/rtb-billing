/* Global Values */
var delay = 300;
var loader = {
    open : function(){
        $("#loader").css("display","block");
    },
    close : function(){
        $("#loader").css("display","none");
    }
}
var message = {
    open : function(text){
        $("#message div.message").text(text);
        $("#message").css("display","block");
    },
    close : function(){
        $("#message").css("display","none");
    }
}
var countries = {
    open : function(text){
        $("#country_select").css("display","block");
    },
    select : function(elem){
        var code = $(elem).attr("data-code");
        var name = $(elem).text();
        var radio = $("#filter_country input").get(1);
        radio.value = code;
        radio.checked = true;
        $("#filter_country_choice").text(name);
        countries.close();
    },
    all : function(){
        $("#filter_country_choice").text("Select Country");
    },
    close : function(){
        $("#country_select").css("display","none");
    }
}
function page_change(){
    $("#search_form input[name='page']").val($("#pagination").val());
    $("#search_form").submit();
}
function page_sort(){
    $("#search_form input[name='sort_by']").val($("#sorting").val());
    $("#search_form input[name='order_by']").val($("#ordering").val());
    $("#search_form").submit();
}
function page_submit(e){
    e.preventDefault();
    $("#search_form input[name='page']").val($("#pagination").val());
    $("#search_form input[name='sort_by']").val($("#sorting").val());
    $("#search_form input[name='order_by']").val($("#ordering").val());
    var data = $(this).serialize();
    loader.open();
    $.ajax({
        method : "GET",
        url : "index.php/search",
        data : data,
        success : function(response,status_code,xhr){
            var result = JSON.parse(response);
            if(result.status == "success"){
                setTimeout(function(){
                    loader.close();
                    // Payload here
                    $("#pagination_container").html(result.pagination);
                    $("#table_container").html(result.table);
                    //-------------
                },delay);
            }
            else {

            }
        }
    });
}
function country_select(e){
    countries.open();
    return false;
}
function column_sort(elem){
    var sorting_by = $(elem).attr('data-sort');
    var current_order = $("#ordering").val();
    var arrow_icon = $(elem).find("span.glyphicon");
    $("#sorting").val(sorting_by);
    if(current_order == "asc"){
        $("#ordering").val("des");
        //arrow_icon.removeClass("glyphicon-menu-up").addClass("glyphicon-menu-down");
    }
    else{
        $("#ordering").val("asc");
        //arrow_icon.removeClass("glyphicon-menu-down").addClass("glyphicon-menu-up");
    }
    $("#search_form").submit();
}
$("#search_form").submit(page_submit);
$(document).ready($("#search_form").submit());