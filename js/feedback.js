!function($){$(function(){
            $("#feedback-form").validate({
                        rules: {
                                subject: "required",
                                message:"required",
                        },
                        messages: {
                                subject:"Please enter the subject",
                                message:"Please enter your feedback"
                        },
                        errorPlacement: function(error, element) {
                                element.parent().find('label:first').append(error);
                        },
                        submitHandler: function() {
                                $('#submit').button('loading')
                                $.ajax({
                                        type:'POST',
                                        url:base_url+'feedback/send',
                                        dataType:'json',
                                        data: {
                                            subject:$('#subject').val(),
                                            message:$('#message').val()
                                        },
                                        success:function(response, xhr) {
                                            
                                            if(response.status == 1) {
                                                $('.modal-body').empty().append(response.message)
                                                $('#myModal').modal("show")
                                                $('#submit').button('reset')
                                                $('form').clearForm()
                                            } else {
                                                $('.modal-body').empty().append(response.message)
                                                $('#myModal').modal("show")
                                                $('#submit').button('reset')
                                            }
                                            $('label.ok').remove()
                                        },
                                        error: function(response) {
                                            $('.modal-body').empty().append(response.message)
                                            $('#myModal').modal("show")
                                            $('#save').button('reset')
                                        }
                                })
                                
                        },
                        success: function(label) {
                                label.html("&nbsp;").addClass("ok");
                        }
                    })    
                    
                    $.fn.clearForm = function() {
                                return this.each(function() {
                                    var type = this.type, tag = this.tagName.toLowerCase()
                                    if (tag == 'form')
                                            return $(':input',this).clearForm()
                                            if (type == 'text' || type == 'password' || tag == 'textarea')
                                                    this.value = ''
                                            else if (type == 'checkbox' || type == 'radio')
                                                    this.checked = false;
                                            else if (tag == 'select')
                                                    this.selectedIndex = -1
                                })
                    }
})}(window.jQuery)