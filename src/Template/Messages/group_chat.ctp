<?php
use Cake\I18n\Time;
use Cake\View\Helper\UrlHelper;

$this->Helpers()->load('BoostCake.Form');
$this->Helpers()->load('GintonicCMS.GtwRequire');

echo $this->GtwRequire->req('message/messages');

?>
<span class="gtw-message">
    <h1><?php echo __('Messages'); ?></h1>
    <div class="row">
        <?php echo $this->element('GtwMessage.header'); ?>
        <div class="col-md-12  col-sm-12">
            <div class="col-md-7  col-sm-7 message-div">
                <div class="col-md-12 col-sm-12 no-padding chat-msg-inner">
                    <?php foreach ($chats as $chat): ?>
                        <div class="chat-message <?php echo ($chat->user_id==$this->Session->read('Auth.User.id'))?'text-right':'text-left'; ?>">
                            <div class="<?php echo ($chat->user_id==$this->Session->read('Auth.User.id'))?'arrow_down ':'arrow_up '; ?><?php echo ((in_array($chat->id, $unReadMessage))&& ($chat->user_id != $this->Session->read('Auth.User.id')))?' text-info ':''?><?php echo (in_array($chat->id, $deletedMessage))?' deleted-message-color':''; ?>">
                                <?php 
                                    if(in_array($chat->id, $deletedMessage)){
                                        echo '<span>'.'This message has been removed.&nbsp;&nbsp;&nbsp;<i class="fa fa-trash-o"></i>'.'</span>';
                                    }else{
                                        if ($chat->user_id == $this->Session->read('Auth.User.id')) {
                                            echo $this->Html->link('<i class="fa fa-trash-o text-danger">&nbsp;</i>', ['plugin' => 'GtwMessage', 'controller' => 'messages', 'action' => 'delete', $chat->id], ['class' => 'delete-message', 'escape' => false]);
                                        }
                                        echo '<span>'.$chat->body.'</span>';
                                    } 
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
                if(!isset($isGroupChat)){
                    echo $this->Form->create('Message', ['id' => 'MessageComposeForm', 'class' => 'messageForm']);
                    echo $this->Form->input('thread_id', ['type' => 'hidden','value' => $threadId]);
                    echo $this->Form->input('user_id', ['type' => 'hidden','value' => $this->Session->read('Auth.User.id')]);
                    echo $this->Form->input('body', ['label' => false,'placeholder' => 'Message body','rows' => '2','cols' => '140','class' => 'wysiwyg']); 
                    echo $this->Form->submit(__('Save'),['class'=>'btn btn-primary']);
                    echo $this->Form->end();
                }
                ?>
            </div>
        </div>
    </div>
</span>