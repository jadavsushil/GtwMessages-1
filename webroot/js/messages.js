define(['jquery', 'basepath','jqueryvalidate','wysiwyg'], function ($, basepath) {
    var $ = require('jquery');
    var jqueryvalidate = require('jqueryvalidate');
    var wysiwyg = require('wysiwyg');
    
    $(document).ready(function() {
        $('.wysiwyg').wysihtml5();
        
        //more-action of index page
        $('.more-action-chk').change(function(){
            var numberOfChecked = $('input:checkbox:checked').not('#check-all').length;
            var totalCheckboxes = $('input:checkbox').not('#check-all').length;
            var numberNotChecked = totalCheckboxes - numberOfChecked;
            if(numberOfChecked == totalCheckboxes){
                $('#check-all').prop('checked',true);
                $('#check-all').closest('tr').addClass('success');
            }else{
                $('#check-all').prop('checked',false);
                $('#check-all').closest('tr').removeClass('success');
            }
            if((numberOfChecked)>0){
               $('.more-action').removeClass('disabled');
            }else{
               $('.more-action').addClass('disabled');
            }
            $(this).closest('tr').toggleClass('success');
        });
        //check all in index page
        $('#check-all').change(function (e){
            var checkboxes = $('#morefunid').find(':checkbox').not('#check-all');
            if($(this).is(':checked')){
                $('table tr').addClass('success');
                checkboxes.prop('checked', true);
                $('.more-action').removeClass('disabled');
            }else{
                $('.more-action').addClass('disabled');
                checkboxes.removeAttr("checked"); 
                $('table tr').removeClass('success');
            }
        });
        
        //more-action perform
        $('.dropdown-menu a').on('click',function(e){
            e.preventDefault();
            var more_action = $(this).data('value');
            if(more_action !=0){
                var checkedValues = $('input:checkbox:checked').map(function() {
                    return this.value;
                }).get();
                var type = $('#gtwMessagetype').val();
                actionUrl = $(this).parents('.dropdown-menu').data('url')+"/"+checkedValues+"/"+more_action+"/"+type;                        
                $("#loadingSpinner").fadeIn();
                $.ajax({
                    url: actionUrl,
                    dataType: 'json',
                    type: 'post',
                    success: function(data) {
                        if(typeof data.redirect !== undefined && data.status === true) {
                            window.location.href = data.redirect;
                        }
                    }
                });
            }
        });
        if($("#MessageForwardForm").length >0){
            $("#MessageForwardForm").validate({
                rules: {
                    "data[Message][title]": {
                        required: true
                    }
                },
                messages: {
                    "data[Message][title]": {
                        required: "Please enter subject"
                    }
                }
            });
        }
        if($("#MessageReplyForm").length >0){
            jQuery("#MessageReplyForm").validate({
                rules: {
                    "data[Message][title]": {
                        required: true
                    }
                },
                messages: {
                    "data[Message][title]": {
                        required: "Please enter subject"
                    }
                }
            });
        }
        if(jQuery("#MessageComposeForm").length>0){
            jQuery("#MessageComposeForm").validate({
                errorClass: 'text-danger',
                rules: {
                    "data[Message][recipient_id]": {
                        required: true
                    },
                    "data[Message][title]": {
                        required: true
                    }
                },
                messages: {
                    "data[Message][recipient_id]": {
                        required: "Please select recipient"
                    },
                    "data[Message][title]": {
                        required: "Please enter subject"
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent('div'));
                }
            });
        }
        $('.messageForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                dataType: 'json',
                type: 'post',
                data: $(this).serialize(),
                success: function(data) {
                    if(typeof data.redirect !== 'undefined' && data.status === true) {
                        window.location.href = data.redirect;
                    }
                }
            });
        });
    });
});
