define(['jquery', 'basepath','jqueryvalidate','wysiwyg'], function ($, basepath) {
    var $ = require('jquery');
    var jqueryvalidate = require('jqueryvalidate');
    var wysiwyg = require('wysiwyg');
    
    $(document).ready(function() {
        function initToolbarBootstrapBindings() {
                    var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
                        'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                        'Times New Roman', 'Verdana'],
                            fontTarget = $('[title=Font]').siblings('.dropdown-menu');
                    $.each(fonts, function (idx, fontName) {
                        fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
                    });
                    $('a[title]').tooltip({container: 'body'});
                    $('.dropdown-menu input').click(function () {
                        return false;
                    })
                            .change(function () {
                                $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
                            })
                            .keydown('esc', function () {
                                this.value = '';
                                $(this).change();
                            });

                    $('[data-role=magic-overlay]').each(function () {
                        var overlay = $(this), target = $(overlay.data('target'));
                        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
                    });
                    if ("onwebkitspeechchange"  in document.createElement("input")) {
                        var editorOffset = $('#editor').offset();
                        $('#voiceBtn').css('position', 'absolute').offset({top: editorOffset.top, left: editorOffset.left + $('#editor').innerWidth() - 35});
                    } else {
                        $('#voiceBtn').hide();
                    }
                }
                ;
                function showErrorAlert(reason, detail) {
                    var msg = '';
                    if (reason === 'unsupported-file-type') {
                        msg = "Unsupported format " + detail;
                    }
                    else {
                        console.log("error uploading file", reason, detail);
                    }
                    $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
                }
                ;
                initToolbarBootstrapBindings();
                $('#editor').wysiwyg({fileUploadError: showErrorAlert});
                window.prettyPrint && prettyPrint();
                
        $('#check-all').change(function (){
            var checkboxes = $('#morefunid').find(':checkbox');    
            if($(this).is(':checked')){
                checkboxes.prop('checked', true);
            }else{
                checkboxes.removeAttr("checked"); 
            }
        });
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
        $("#inbox-table input[type='checkbox']").change(function() {
            $(this).closest('tr').toggleClass("highlight", this.checked);
        });
    });
});
