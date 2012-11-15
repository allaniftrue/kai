!function($){$(function(){
    $('[id^=tooltip-top],#message').tooltip({
            placement:'top',
            html:true
    })
    
    $('[id^=tooltip-bottom]').tooltip({
            placement:'bottom',
            html:true
    })
    
    $('#message').click(function(){
        $.ajax({
            type:"post",
            url:base_url+'payments/message',
            dataType:"json",
            data:{mid:$(this).attr('data-id')},
            success:function(json){
                $("#myModalLabel").empty().append(json.title)
                $(".modal-body").empty().append(json.message)
                $("#myModal").modal('show')
            },
            error:function(){
                
            }
        })
    })
})}(window.jQuery)