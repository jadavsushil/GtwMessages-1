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
        <?php
        echo $this->Form->create('Message', ['id' => 'MessageComposeForm', 'class' => 'messageForm']);
//        echo $this->Form->select('recipient_id', $users, [
//            'default' => !empty($message->user_id) ? $message->user_id : '',
//            'empty' => 'Select Recipient',
//            'class' => 'selectpicker form-group form-control',
//            'data-live-search' => 'true'
//        ]);
        ?>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><?php echo __('To')?></span>
                <?php 
                echo $this->Form->select('recipient_id', $users, [
            'default' => !empty($message->user_id) ? $message->user_id : '',
            'empty' => 'Select Recipient',
            'class' => 'selectpicker form-group form-control',
            'data-live-search' => 'true'
        ]);
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><?php echo __('Subject')?></span>
                <input type="text" name="title" class="form-control" id="title" placeholder="<?php echo __('Subject of message')?>" value="<?php echo $subject;?>">
            </div>
        </div>
        <?php 
//        echo $this->Form->input('title', [
//            'label' => false,
//            'placeholder' => 'Subject',
//            'value' => $subject,
//            'before' => '<div class="input-group"><span class="input-group-addon">Subject:</span>',
//            'after' => '</div>'
//        ]);
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