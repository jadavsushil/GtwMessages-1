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
        scollTopChat();
        function scollTopChat(fromNew){
            if(fromNew && typeof(messageDivHeight) !== "undefined"){
                $(".chat-msg-inner").scrollTop(messageDivHeight +100);
            }else{
                if(typeof($('.chat-msg-inner div.chat-message:last').offset()) !== "undefined"){
                    messageDivHeight = $('.chat-msg-inner div.chat-message:last').offset().top;
                    $(".chat-msg-inner").scrollTop(messageDivHeight);
                }
            }
        }
        $('.messageForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                dataType: 'json',
                type: 'post',
                data: $(this).serialize(),
                success: function (data) {
                    if (data.status) {
                        //clear old message
                        $('.wysiwyg').data('wysihtml5').editor.clear();
                        //add new message on display
                        $('.chat-msg-inner').append(data.content);
                        //scroll top
                        scollTopChat(true);
                        //bind delete event
                        $('.delete-message').unbind('click');
                        bindDelete();
                    }
                }
            });
        });
        //delete-message
        bindDelete();
        function bindDelete(){
            $('.delete-message').on('click',function(e)
            {
                e.preventDefault();
                var curDiv  = $(this);
                if (confirm('Do you want to delete this message ?'))
                {
                    $.ajax({
                        url: curDiv.attr('href'),
                        dataType: 'json',
                        success: function (data)
                        {
                            if (typeof data.status !== 'undefined' && data.status === 'success')
                            {
                                curDiv.parent().addClass('deleted-message-color');
                                curDiv.parent().html('This message has been removed.&nbsp;&nbsp;&nbsp;<i class="fa fa-trash-o"></i>');
                            }
                        }
                    });
                }
            });
        }
        //create token
        $.ajax({
           url:'/gtw_message/messages/setUserCommaSepList',
           dataType:'json',
           success:function(response){
                    $('.tokenfield').tokenInput(response, {
                    theme: "facebook",
                    preventDuplicates: true,
                    hintText: "Enter existing user email address",
                    noResultsText: "No user found.",
                    searchingText: "Searching..."
                });
           },
           error:function(e){
               console.log(e);
           }
        });
        
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