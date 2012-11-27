!function($){$(function(){
    $('#remove,#claimed,#message,#download').tooltip({
			placement:'left',
                        html:true
    })
    
    $('[id^=remove]').live('click', function(){
        var $this = $(this);
        var conf = confirm('Are you sure you want to remove this transaction?');
        var $id = $this.attr('data-id');
        if(conf) {
            $.ajax({
                type:'post',
                url:[base_url,'transactions/remove'].join(''),
                dataType:'json',
                data:{id:$id},
                success:function(response){
                    if(response.status == 1) {
                        var trLn = $this.parents('tbody tr').siblings().length;
                        if(trLn == 0) {
                            $this.parents('tr').fadeOut('slow', function(){ $(this).remove() });
                            window.location.reload(true);
                        } else {
                            $this.parents('tr').fadeOut('slow', function(){ $(this).remove() });
                        }
                        var newVal = $('#total-unmanaged').text()-1;
                        $('#total-unmanaged').empty().append(newVal);
                    } else {
                        $("#myModalLabel").empty().append("Error");
                        $(".modal-body").empty().append(['<p>',response.message,'</p>'].join(''));
                        $("#myModal").modal('show');
                    }
                },
                error:function(){
                    $("#myModalLabel").empty().append("Error");
                    $(".modal-body").empty().append("Unable to process request");
                    $("#myModal").modal('show');
                }
            });
        } else {
            return false;
        }
    });
    
    $('[id^=claimed]').live("click",function(){
        var $this = $(this);
        var $id = $this.attr('data-id');
        $.ajax({
           type:"post",
           url:[base_url,'transactions/claimed'].join(''),
           dataType:'json',
           data:{id:$id},
           success:function(response) {
               if(response.status == 1) {
                   var trLn = $this.parents('tbody tr').siblings().length;
                   if(trLn == 0) {
                       window.location.reload(true);
                   } else {
                       $this.parents('tr').attr('class','success').delay(1000).fadeOut('slow', function(){ $(this).remove() });
                   }
                   var newVal = $('#total-unmanaged').text()-1;
                    $('#total-unmanaged').empty().append(newVal);
               }else {
                    $("#myModalLabel").empty().append("Error");
                    $(".modal-body").empty().append(['<p>',response.message,'</p>'].join(''));
                    $("#myModal").modal('show');
               }
           },
           error:function() {
                $("#myModalLabel").empty().append("Error");
                $(".modal-body").empty().append("Unable to process request");
                $("#myModal").modal('show');
           }
        }); return false;
    });
    
    $('a#message').live("click", function(){
        $.ajax({
            type:"post",
            url:[base_url,'payments/message'].join(''),
            dataType:"json",
            data:{mid:$(this).attr('data-id')},
            success:function(json){
                $("#myModalLabel").empty().append(json.title)
                $(".modal-body").empty().append(json.message)
                $("#myModal").modal('show')
            },
            error:function(){
                $("#myModalLabel").empty().append("Error")
                $(".modal-body").empty().append("Unable to fetch message")
                $("#myModal").modal('show')
            }
        })
    })
    
})}(window.jQuery)