!function ($) {
	$(function(){
                /*@TODO
                 * 
                 * Fix to fir registration page example page is http://localhost/startup/ref/admin
                 */
                $('#tooltip-right').tooltip({trigger:'hover',html:true,placement:"right"});
                $("#registrationform").validate({
                        rules: {
                                referrer: {
                                    required:false,
                                    notEqualTo:['#username']
                                },
                                sponsor: {
                                    required:false,
                                    notEqualTo:['#username']
                                },
                                username:{
                                    required:true,
                                    notEqualTo:['#referrer','#sponsor']
                                },
                                password:{
                                    required: true,
                                    minlength:8
                                },
                                password_c:{
                                    equalTo:'#password'
                                },
                                firstname: "required",
                                lastname: "required",
                                email: {
                                        required: true,
                                        email: true
                                },
                                contact: {
                                        required: true,
                                        digits: true
                                },
                                address: "required",
                                question:"required",
                                agree: "required"
                        },
                        messages: {
                                username:"Enter a unique username",
                                password: {
                                    required:"Enter at least 8 characters, check the guidelines",
                                    minlength: "Enter at least 8 characters, check the guidelines"
                                },
                                firstname: "Enter your firstname",
                                lastname: "Enter your lastname",
                                email: {
                                        required: "Please enter a valid email address",
                                        email: "Please enter a valid email address"
                                },
                                contact:{
                                    required:"Enter your contact number",
                                    digits:"Only digits are accepted"
                                },
                                address:"Enter your full address",
                                question:"You need to answer the question",
                                agree:""
                        },
                        errorPlacement: function(error, element) {
                                element.parent().find('label:first').append(error);
                        },
                        submitHandler: function() {
                                $('#save').button('loading')
                                $.ajax({
                                        type:'POST',
                                        url:base_url+'ref/register',
                                        dataType:'json',
                                        data: {
                                            referrer:$('#referrer').val(),
                                            sponsor:$('#sponsor').val(),
                                            username:$('#username').val(),
                                            password:$('#password').val(),
                                            lastname:$('#lastname').val(),
                                            firstname:$('#firstname').val(),
                                            email:$('#email').val(),
                                            contact:$('#contact').val(),
                                            address:$('#address').val(),
                                            question:$('#question').val()
                                        },
                                        success:function(response, xhr) {
                                            
                                            if(response.status === 0 && response.id === 'username') {
                                                $('#'+response.id).focus()
                                                $('label.ok').remove()
                                                $('#save').button('reset')
                                            } else if(response.status === 0 && response.id != 'username' && response.id != ''){
                                                $('#'+response.id).focus()
                                                $('label.ok').remove()
                                                $('#save').button('reset')
                                                $('#qholder').empty().append(response.question[0])
                                            } else {
                                                $('.modal-body').empty().append('<p>'+response.msg+'')
                                                $('#myModal').modal("show")
                                                $('label.ok').remove()
                                                $('#save').button('reset')
                                                $('form').clearForm()
                                                $('#qholder').empty().append(response.question[0])
                                                return false
                                            }
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
                    
                    $.validator.addMethod("notEqualTo",
                                function(value, element, param) {
                                    var notEqual = true;
                                    value = $.trim(value);
                                    for (i = 0; i < param.length; i++) {
                                        if (value == $.trim($(param[i]).val())) { notEqual = false; }
                                }return this.optional(element) || notEqual;
                    }, "Please enter a diferent value.")
                    
                    $.fn.clearForm = function() {
                            return this.each(function() {
                                    var type = this.type, tag = this.tagName.toLowerCase();
                                    if (tag == 'form')
                                            return $(':input',this).clearForm();
                                            if (type == 'text' || type == 'password' || tag == 'textarea')
                                                    this.value = '';
                                            else if (type == 'checkbox' || type == 'radio')
                                                    this.checked = false;
                                            else if (tag == 'select')
                                                    this.selectedIndex = -1;
                                    });
                    }
        })
}(window.jQuery)