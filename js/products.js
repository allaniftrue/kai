!function($){$(function(){
        $('#avatar-holder,[id*=tooltip-right]').tooltip({
            placement:'right',
            html:true
        });
    
        $('#fileselectbutton').click(function(e){
            $('#file').trigger('click');
        });

        $('#file').change(function(e){
            var val = $(this).val();
            var file = val.split(/[\\/]/);
            $('#filename').val(file[file.length-1]);
        });
        
        /* file upload */
        $('#avatar-holder').live({
                mouseenter: function() {
                        $('#productuploader').css({
                                "display":"inline",
                                "z-index":10
                        });
                },
                mouseleave: function() {
                        $('#productuploader').hide();
                }
        })
        var $fub = $('#productuploader');
        var $messages = $('#messages');
        var addedFiles = 0;
        var fileLimit = 1;
        var uploader = new qq.FileUploaderBasic({
              button: $fub[0],
              action: [base_url,'products/screenshot'].join(''),
              debug: false,
              allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
              sizeLimit: 204800, // 200 kB = 200 * 1024 bytes
              onSubmit: function(id, fileName) {
                var $msg = ['<div id="file-', id , '" class="alert" style="margin: 20px 0 0"></div>'].join('');
                $messages.append($msg) ;
              },
              onUpload: function(id, fileName) {
                $('#file-' + id).addClass('alert-info')
                                .html(['<img src="',base_url,'img/preloader.gif" alt="Initializing. Please hold."> Initializing “',fileName,'”'].join(''));
              },
              onProgress: function(id, fileName, loaded, total) {
                if (loaded < total) {
                  progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
                  $('#file-' + id).removeClass('alert-info')
                                  .html(['<img src="',base_url,'img/preloader.gif" alt="In progress. Please hold."> Uploading “',fileName,'”', progress].join(''));
                } else {
                  $('#file-' + id).addClass('alert-info')
                                  .html(['<img src="',base_url,'img/preloader.gif" alt="Saving. Please hold."> Saving “',fileName,'”'].join(''))
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
                                    .html(['<i class="icon-ok"></i> ',fileName,'&nbsp;&nbsp; &mdash; <a href="javascript:void(0)" data-id="',responseJSON.filename,'" id="remove" class="attachment" title="Remove">Remove</a>'].join(''));
                    var $img=[base_url,'_products/',responseJSON.filename].join('');
                    $('.img-polaroid').attr('src',$img);
                  } else {
                    $('#file-' + id).removeClass('alert-info')
                                    .addClass('alert-error')
                                    .html(['<i class="icon-exclamation-sign"></i> Error with “',fileName,'”: ',responseJSON.message].join(''))
                  }
              }
            }); 
            $("#remove").live("click", function(){
                var $filename = $(this).attr("data-id")
                var $parent = $(this).closest("div")
                $.ajax({
                        type:"post",
                        url:[base_url,"products/remove"].join(''),
                        dataType:"json",
                        data:{file:$filename},
                        success:function(json){
                            if(json.status === 1) {
                                $fub.show();
                                addedFiles = 0;
                                $parent.empty().hide("slow",function(){
                                   $(this) .remove();
                                   var $img=[base_url,'img/products.png'].join('');
                                   $('.img-polaroid').attr('src',$img);
                                   $('#avatar-holder').trigger('mouseover');
                                });
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
                });
            });
            
            /* end of file upload */
            $('#product-form').validate({
                rules: {
                    name:'required',
                    cost:{
                        required:true,
                        digits:true
                    },
                    filename:"required",
                    description:"required"
                },
                errorPlacement: function(error, element) {
                        element.parent().find('label:first').append(error);
                },
                submitHandler: function() {
                    $('#add-new-product').button('loading');
                    $('#product-form').submit();
                },
                success: function(label) {
                    label.html("&nbsp;").addClass("ok");
                }
            });
            
})}(window.jQuery)