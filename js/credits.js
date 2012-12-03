!function($){$(function(){
    $('[id^=add-credits],[id^=sub-credits]').tooltip({
            placement:'top',
            html:true
    });
    
    var $id='';
    var $this = '';
    $('[id*=add-credits]').live("click",function(){
        $this=$(this);
        $id=$this.attr('data-id');
        var form = ['<form action="" id="credits-form" method="post"><p><label for="points" class="required"><strong>Credit Points</strong></label><input class="input input-large" name="points" id="points" type="text" /></p><p><button class="btn btn-success" type="button" data-loading-text="Adding Credits..." id="add-points">Add Points</button></p><input type="hidden" id="',$id,'" name="id", id="id" /></form></div>'].join('');
        $('#myModalLabel').empty().append('Adding of Credits');
        $('.modal-body').empty().append(form);
        $('#myModal').modal("show");
    });
    
    $('[id*=add-points]').live("click",function(){
        var ptsVal = $('[id*=points]').val();
        var $btn = $(this);
        $btn.button('loading');
        if($.isNumeric(ptsVal)) {
            $.ajax({
                type:'post',
                url:[base_url,'credits/add'].join(''),
                dataType:'json',
                data:{id:$id,points:ptsVal},
                success:function(response) {
                    if(response.status == 1){
                        $('#credits-form').prepend(['<div class="alert alert-success input-block-level"><i class="icon-ok-circle"></i>&nbsp; ',response.message,'</div>'].join(''));
                        var $td = $this.parent().prev();
                        var curVal = parseInt($td.text());
                        var newVal = curVal+parseInt(ptsVal);
                        $td.empty().text(newVal);
                    }else {
                       $('#credits-form').prepend(['<div class="alert alert-erro input-block-level"><i class="icon-exclamation-sign"></i>&nbsp; ',response.message,'</div>'].join(''));
                    }
                    $btn.button('reset');
                    return false;
                },
                error:function(){
                     $('#credits-form').prepend('<div class="alert alert-error input-block-level"><i class="icon-exclamation-sign"></i>&nbsp;Unable to process request');
                    $btn.button('reset');
                    return false;
                }
            });
        } else {
            $('#points').focus().val(); return false;
        }
    });
    
    $('[id*=sub-credits]').live("click",function(){
        $this=$(this);
        $id=$this.attr('data-id');
        var form = ['<form action="" id="credits-form" method="post"><p><label for="points" class="required"><strong>Credit Points</strong></label><input class="input input-large" name="points" id="points" type="text" /></p><p><button class="btn btn-success" type="button" data-loading-text="Subtracting Credits..." id="sub-points">Subtract Points</button></p><input type="hidden" id="',$id,'" name="id", id="id" /></form></div>'].join('');
        $('#myModalLabel').empty().append('Subtracting of Credits');
        $('.modal-body').empty().append(form);
        $('#myModal').modal("show");
    });
    
    $('[id*=sub-points]').live("click",function(){
        var ptsVal = $('#points').val();
        var $btn = $(this);
        var $td = $this.parent().prev();
        var curVal = parseInt($td.text());
        if($.isNumeric(ptsVal)) {
            
            if(ptsVal > curVal) {
                $('#credits-form').prepend(['<div class="alert alert-error input-block-level"><i class="icon-exclamation-sign"></i>&nbsp;Value must not be greater than ',curVal,'</div>'].join(''));
                return false;
            } else {
                $btn.button('loading');
                $.ajax({
                    type:'post',
                    url:[base_url,'credits/subtract'].join(''),
                    dataType:'json',
                    data:{id:$id,points:ptsVal},
                    success:function(response) {
                        if(response.status == 1){
                            $('#credits-form').prepend(['<div class="alert alert-success input-block-level"><i class="icon-ok-circle"></i>&nbsp; ',response.message,'</div>'].join(''));
                            $td.empty().text(curVal-parseInt(ptsVal));
                        }else {
                           $('#credits-form').prepend(['<div class="alert alert-erro input-block-level"><i class="icon-exclamation-sign"></i>&nbsp; ',response.message,'</div>'].join(''));
                        }
                        $btn.button('reset');
                        return false;
                    },
                    error:function(){
                         $('#credits-form').prepend('<div class="alert alert-error input-block-level"><i class="icon-exclamation-sign"></i>&nbsp;Unable to process request');
                        $btn.button('reset');
                        return false;
                    }
                });
            }
        } else {
            $('#points').focus().val(); return false;
        }
    });
    
})}(window.jQuery)