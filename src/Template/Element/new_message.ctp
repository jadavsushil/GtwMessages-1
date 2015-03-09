<div class="chat-message text-right">
    <div class="arrow_down text-info">
        <?php 
        echo $this->Html->link('<i class="fa fa-trash-o text-danger">&nbsp;</i>', ['plugin' => 'GtwMessage', 'controller' => 'messages', 'action' => 'delete', $message['id']], ['class' => 'delete-message','onclick'=>'deletemessage(this)', 'escape' => false]);
        ?>
        <span>
            <?php echo $message['body']?>
        </span>
    </div>
</div>