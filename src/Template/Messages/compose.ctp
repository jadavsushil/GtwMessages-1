<?php

use Cake\I18n\Time;
use Cake\View\Helper\UrlHelper;

$this->Helpers()->load('BoostCake.Form');
$this->Helpers()->load('GintonicCMS.GtwRequire');

echo $this->GtwRequire->req('message/messages');

$subject = $body = '';
$submitButton = __('Send');
if ($messageType == 'reply') {
    $submitButton = __('Reply');
    $subject = 'Re:' . $message->title;
    $body = "On " . Time::parse($message->created->i18nFormat()) . ", " . $message->Sender->first . ' ' . $message->Sender->last . " <" . $message->Sender->email . "> wrote:<br />< <blockquote>" . $message->body . "</blockquote>";
} elseif ($messageType == 'forward') {
    $submitButton = __('forward');
    $subject = 'Fwd:' . $message->title;
    $body = "On " . Time::parse($message->created->i18nFormat()) . ", " . $message->Sender->first . ' ' . $message->Sender->last . " <" . $message->Sender->email . "> wrote:<br />< <blockquote>" . $message->body . "</blockquote>";
}
?>
<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.4/css/bootstrap-select.css">
<h1><?php echo __('Messages'); ?></h1>

<div class="row">        
    <div class="col-md-2 col-xs-3">
        <?php echo $this->element('GtwMessage.leftpanel', ['type' => $messageType]); ?>
    </div>
    <div class="col-md-10  col-xs-9">
        <div class="hero-unit">
                <div class="pull-right">
                    <div class="fb-like" data-href="http://facebook.com/mindmupapp" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div><br/>
                    <a href="https://twitter.com/mindmup" class="twitter-follow-button" data-show-count="true" data-show-screen-name="true" data-lang="en">Follow @mindmup</a> 
                </div>
                <h1>bootstrap-wysiwyg <br/> <small>tiny wysiwyg rich text editor for Bootstrap</small></h1>
                <hr/>
                <div id="alerts"></div>
                <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="icon-font"></i><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                        </ul>
                    </div>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
                            <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
                            <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
                        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
                        <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
                        <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
                        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
                        <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
                        <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
                        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
                        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
                        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
                        <div class="dropdown-menu input-append">
                            <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
                            <button class="btn" type="button">Add</button>
                        </div>
                        <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>

                    </div>

                    <div class="btn-group">
                        <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
                        <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
                        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
                    </div>
                    <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
                </div>

                <div id="editor">
                    Go ahead&hellip;
                </div>
            </div>
        <?php
        echo $this->Form->create('Message', ['id' => 'MessageComposeForm', 'class' => 'messageForm']);
        echo $this->Form->select('recipient_id', $users, [
            'default' => !empty($message->user_id) ? $message->user_id : '',
            'empty' => 'Select Recipient',
            'class' => 'selectpicker form-group',
            'data-live-search' => 'true'
        ]);
        echo $this->Form->input('title', [
            'label' => false,
            'placeholder' => 'Subject',
            'value' => $subject,
            'before' => '<div class="input-group"><span class="input-group-addon">Subject:</span>',
            'after' => '</div>'
        ]);
        echo $this->Form->input('body', [
            'label' => false,
            'placeholder' => 'Message body',
            'value' => $body,
            'rows' => '15',
            'cols' => '140',
            'class' => 'wysiwyg',
        ]);
        ?>
        <div class="modal-footer clearfix">
            <div class='pull-left'>
                <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> <?php echo $submitButton; ?></button>
                <?php echo $this->Html->link('cancel', ['controller' => 'messages', 'action' => 'index', 'plugin' => 'GtwMessage'], ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>