!function($){$(function(){
    
    var $td = ""
    $('[id^=tooltip-top]').tooltip({
			placement:'top',
                        html:true
    })
    
    $('.remove').live("click",function(){
        var conf = confirm('Are you sure you want to remove this user?')
        var $id = $(this).attr('data-id')
        var $this = $(this)
        if(conf) {
            $.ajax({
                type:"post",
                url:base_url+"all_users/remove",
                dataType:"json",
                data:{id:$id},
                success:function(response) {
                    $('#myModalLabel').empty().append('Result')
                    if(response.status === 1) {
                        $this.closest("tr").hide('slow')
                    } else {
                        $('.modal-body').empty().append(['<p>',response.message,'</p>'].join(''));
                        $('#myModal').modal("show")
                    }
                },
                error:function() {
                    $('#myModalLabel').empty().append('Result')
                    $('.modal-body').empty().append('<p>Unable to process request</p>')
                    $('#myModal').modal("show")
                }
            })
        } return false
    })
    
    $('.modify').live("click",function() {
        var $id = $(this).attr('data-id')
        $td = $(this)
        $.ajax({
            type: "post",
            url:base_url+"all_users/users",
            dataType:"json",
            data:{id:$id},
            success: function(response) {
                if(response.status === 1) {
                    var listLn = response.list.length
                    var option_referrer = []
                    var option_sponsor = []
                    var currentUsername = $td.parent().parents(':eq(2)').siblings(':eq(0)').text();
                    for(var i=0; i<listLn; i++) {
                        if(response.list[i] != response.referrer) {
                            if(response.list[i] != currentUsername)
                                option_referrer.push('<option value="',response.list[i],'">',response.list[i],'</option>');
                        }
                        if(response.list[i] != response.sponsor) {
                            if(response.list[i] != currentUsername)
                                option_sponsor.push('<option value="',response.list[i],'">',response.list[i],'</option>');
                        }
                    }
                    
                    var optionReferrer = option_referrer.join('');
                    var optionSponsor = option_sponsor.join('');
                    
                    var referrer = [];
                    var sponsor = [];
                    if(response.referrer == null || typeof response.referrer == 'undefined' ) {
                        referrer = '<option value="">Select a user</option>';
                    } else {
                        referrer = ['<option value="',response.referrer,'">',response.referrer,'</option>'].join('');
                    }
                    
                    if(response.sponsor == null || typeof response.sponsor == 'undefined' ) {
                        sponsor = '<option value="">Select a user</option>'
                    } else {
                        sponsor = ['<option value="',response.sponsor,'">',response.sponsor,'</option>'].join('');
                    }
                    $('#myModalLabel').empty().append(['Adding a referrer/sponsor to <em>', response.username,'</em>'].join(''));
                    $('.modal-body').empty().append(['<p><span id="message-holder"></span></p><form id="referrer-sponsor-form" action="" method="post"><p><input type="hidden" name="curid" id="curid" value="',$id,'"><label for="referrer"><strong>Referrer</strong></label><select name="referrer" id="referrer" class="input input-xlarge">',referrer,optionReferrer,'</select></p><p><label for="referrer"><strong>Sponsor</strong></label><select name="sponsor" id="sponsor" class="input input-xlarge">',sponsor+optionSponsor,'</select></p><p><button class="btn btn-success" id="btn-referrer-sponsor" data-loading-text="Saving Information...">Save Information</button></p></form>'].join(''));
                    $('#myModal').modal("show");
                    
                } else {
                    $('#myModalLabel').empty().append('Error')
                    $('.modal-body').empty().append(['<p>',response.message,'</p>'].join(''));
                    $('#myModal').modal("show")
                }
            },
            error:function() {
                $('#myModalLabel').empty().append('Result')
                $('.modal-body').empty().append('<p>Unable to process request</p>')
                $('#myModal').modal("show")
            }
        })
    })

    $('#btn-referrer-sponsor').live("click",function(){
        var $this = $(this)
        var referrer_val = $('#referrer').val()
        var sponsor_val = $('#sponsor').val()
        var $id = $("#curid").val()
        $this.button('loading')
        $.ajax({
            type:"post",
            url:base_url+"all_users/sponsor_referrer",
            dataType:'json',
            data:{id:$id,referrer:referrer_val,sponsor:sponsor_val},
            success: function(response) {
                if(response.status === 1) {
                    $('#message-holder').attr('class','alert alert-success input-block-level').empty().append(['<i class="icon-ok-circle"><i> ',response.message].join(''));
                    $td.parent().prev().prev().empty().append(sponsor_val)
                    $td.parent().prev().prev().prev().empty().append(referrer_val)
                } else {
                    $('#message-holder').attr('class','alert alert-error input-block-level').empty().append('<i class="icon-exclamation-sign"><i> '+ response.message)
                }
                $this.button('reset')
            },
            error:function(){
                $('#message-holder').attr('class','alert alert-error input-block-level').empty().append('<i class="icon-remove-circle"><i> Unable to process request')
                $this.button('reset')
            }
        })
        return false
    })
    
    $('.promote').live('click',function() {
        var $this = $(this);
        var $id=$this.attr('data-id');
        var conf = confirm('Are you sure you want to change the account type?');
        if(conf) {
            $.ajax({
                type:'post',
                url:[base_url,'all_users/toggle_type'].join(''),
                dataType:'json',
                data:{id:$id},
                success:function(response){
                    if(response.status === 1) {
                        var utype = response.type === 'admin' ? 'Administrator' : 'Normal User';
                        $this.parents(':eq(3)').prev().empty().append(utype).fadeIn('slow');
                    } else {
                        $('#myModalLabel').empty().append('Error')
                        $('.modal-body').empty().append(['<p>',response.message,'</p>'].join(''));
                        $('#myModal').modal("show")
                    }
                },
                error:function(){
                    $('#myModalLabel').empty().append('Error')
                    $('.modal-body').empty().append('There was an error while processing your request');
                    $('#myModal').modal("show")
                }
            });
        } else {
            return false;
        }
    });
})}(window.jQuery)