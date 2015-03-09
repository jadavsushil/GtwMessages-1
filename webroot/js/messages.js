define(['jquery', 'basepath', 'jqueryvalidate', 'wysiwyg','message/bootstrap-tokenfield.min'], function ($, basepath) {
    var $ = require('jquery');
    var jqueryvalidate = require('jqueryvalidate');
    var wysiwyg = require('wysiwyg');
    var tokens = require('message/bootstrap-tokenfield.min');

    $(document).ready(function () {
        $('.wysiwyg').wysihtml5();
        jQuery("#MessageComposeForm").validate({
            errorClass: 'text-danger',
            rules: {
                "body": {
                    required: true
                }
            },
            messages: {
                "body": {
                    required: "Please enter something."
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent('div'));
            }
        });
        
        $(".chat-msg-inner").scrollTop($('.chat-msg-inner').height()+150);
        $('.messageForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                dataType: 'json',
                type: 'post',
                data: $(this).serialize(),
                success: function (data) {
                    if (data.status) {
                        $('.chat-msg-inner').append(data.content);
                        //$('.chat-msg-inner .delete-message').bind('click');
                        //bindDelete();
                        //window.location.href = data.redirect;
                    }
                }
            });
        });
        
        //delete chat-message
        function deletemessage(curDiv) {
            alert('ad');
            if(confirm('Do you want to delete this message ?')){
                $.ajax({
                    url: curDiv.attr('href'),
                    dataType: 'json',
                    success: function (data) {
                        if (typeof data.status !== 'undefined' && data.status === 'success') {
                            curDiv.parent().addClass('deleted-message-color');
                            curDiv.parent().html('This message has been removed.&nbsp;&nbsp;&nbsp;<i class="fa fa-trash-o"></i>');
                        }
                    }
                });
            }
        }
        
        //change status
        $('.change-message-status').on('click',function(e){
            e.preventDefault();
            var curEle = $(this);
            var status = $(this).data('status');
            alert(status);
            params = 0;
            if(status == 'read'){
                params = 1;
            }
            $.ajax({
                url: $(this).attr('href') + "/" + params,
                dataType: 'json',
                success: function (data) {
                    if (typeof data.status !== 'undefined' && data.status === 'success') {
                        if(status === 'read'){
                            curEle.parent().removeClass('text-info');
                            curEle.text('unread');
                            curEle.data('status','unread');
                        }else{
                            curEle.parent().addClass('text-info');
                            curEle.text('read');
                            curEle.data('status','read');
                        }
                    }
                }
            });
        });
        
    });
    
    
});
