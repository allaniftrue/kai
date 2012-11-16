!function($){$(function(){
    $('[id^=tooltip-top],#message,#attachment').tooltip({
            placement:'top',
            html:true
    })
    
    $('[id^=tooltip-bottom]').tooltip({
            placement:'bottom',
            html:true
    })
    
    $('a#message').live("click", function(){
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
                $("#myModalLabel").empty().append("Error")
                $(".modal-body").empty().append("Unable to fetch message")
                $("#myModal").modal('show')
            }
        })
    })
    
    /* file upload */
    $fub = $('#attachment');
    $messages = $('#messages');
    var addedFiles = 0;
    var fileLimit = 1;
 
    var uploader = new qq.FileUploaderBasic({
      button: $fub[0],
      action: base_url+'payments/upload',
      allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
      sizeLimit: 20480000,
      onSubmit: function(id, fileName) {
                                        $messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>')
      },
      onUpload: function(id, fileName) {
        $('#file-' + id).addClass('alert-info')
                        .html('<img src="'+base_url+'"img/preloader.gif" alt="Initializing. Please hold."> ' +
                              'Initializing ' +
                              '“' + fileName + '”')
      },
      onProgress: function(id, fileName, loaded, total) {
        if (loaded < total) {
          progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
          $('#file-' + id).removeClass('alert-info')
                          .html('<img src="client/loading.gif" alt="In progress. Please hold."> ' +
                                'Uploading ' +
                                '“' + fileName + '” ' +
                                progress)
        } else {
          $('#file-' + id).addClass('alert-info')
                          .html('<img src="client/loading.gif" alt="Saving. Please hold."> ' +
                                'Saving ' +
                                '“' + fileName + '”')
        }
      },
      onComplete: function(id, fileName, responseJSON) {
        if (responseJSON.success) {
          addedFiles ++;
          if(addedFiles >= fileLimit) {
              $fub.hide().tooltip('hide')
          }
          $('#file-' + id).removeClass('alert-info')
                          .addClass('alert-success')
                          .html('<i class="icon-ok"></i> ' + fileName + '&nbsp;&nbsp; &mdash; <a href="javascript:void(0)" data-id="' +
                          responseJSON.filename+'" id="remove" class="attachment" title="Remove">Remove</a>')
        } else {
          $('#file-' + id).removeClass('alert-info')
                          .addClass('alert-error')
                          .html('<i class="icon-exclamation-sign"></i> ' +
                                'Error with ' +
                                '“' + fileName + '”: ' +
                                responseJSON.error)
        }
      }
    })
    /* file upload */
    $("#remove").live("click", function(){
        var $filename = $(this).attr("data-id")
        var $parent = $(this).closest("div")
        $.ajax({
                type:"post",
                url:base_url+"payments/unattach",
                dataType:"json",
                data:{file:$filename},
                success:function(json){
                    if(json.status === 1) {
                        $fub.show()
                        addedFiles = 0
                        $parent.empty().hide("slow")
                    } else {
                        $("#myModalLabel").empty().append(json.title)
                        $(".modal-body").empty().append(json.message)
                        $("#myModal").modal('show')
                    }
                },
                error:function(){
                    $("#myModalLabel").empty().append("Error")
                    $(".modal-body").empty().append("An error occurred in removing the file")
                    $("#myModal").modal('show')
                }
        })
    })
    
    /* validate form */
    var global = {
        "theElement":""
    }
    $("#receipt-info-form").validate({
                        rules: {
                                paymentcenter: "required",
                                transaction: "required",
                                amount: {
                                        required: true,
                                        number: true
                                }
                        },
                        messages: {
                                paymentcenter: "Please enter the name of the payment center",
                                transaction: "Please enter the transaction ID",
                                amount: {
                                        required: "Please enter the amount you paid",
                                        number: "Only numbers are allowed"
                                }
                        },
                        errorPlacement: function(error, element) {
                                    global.theElement = element
                                    element.css({
                                                  "border-color":"#b94a48",
                                                  "-webkit-box-shadow":"inset 0 1px 1px rgba(0, 0, 0, 0.075)",
                                                  "-moz-box-shadow":"inset 0 1px 1px rgba(0, 0, 0, 0.075)",
                                                  "box-shadow":"inset 0 1px 1px rgba(0, 0, 0, 0.075)"
                                    })
                        },
                        submitHandler: function() {

                                $.ajax({
                                        type:'POST',
                                        url:base_url+'settings/save_profile',
                                        dataType:'json',
                                        data: {
                                            lastname:$('#lastname').val(),
                                            firstname:$('#firstname').val(),
                                            email:$('#email').val(),
                                            contact:$('#contact').val(),
                                            address:$('#address').val()
                                        },
                                        success: function(response) {
                                            $('.modal-body').empty().append('<p>'+response.message+'')
                                            $('label.ok').remove()
                                        },
                                        error: function(response) {
                                            $('.modal-body').empty().append(response.message)
                                        }
                                })
                                $('#myModal').modal("show");
                        },
                        success: function(sample) {
                            global.theElement.removeAttr("style")
                        }
    })
    
    $('#paymentcenter').typeahead({
                minLength:2,
                source: function(query, process) {
                        return $.ajax({
                                url: base_url+"payments/paymentcenters",
                                type: 'post',
                                data: {paymentcenter: $('#paymentcenter').val()},
                                dataType: 'json',
                                success: function(json) {
                                    return typeof json == 'undefined' ? false : process(json);
                                }
                        })
                }
    })
})}(window.jQuery)