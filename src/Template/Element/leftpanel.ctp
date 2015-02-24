<?php 
$newMessageCount = empty($newMessageCount)?'':' ('.$newMessageCount.')';
$type = isset($type)?$type:'';
$arrTypes = array(
                'compose' => '<i class="fa fa-pencil"></i> Compose Message',
                'inbox' => '<i class="fa fa-inbox"></i> Inbox '.$newMessageCount,
                'sent' => '<i class="fa fa-mail-forward"></i> Sent',
                'trash' => '<i class="fa fa-trash-o"></i> Trash',
                'reply' => '<i class="fa fa-reply"></i> Reply Message',
                'view' => '<i class="fa fa-envelope-o"></i> Message Detail',
                'forward' => '<i class="fa fa-mail-forward"></i> Forward Message',
            );
?>
<h4 style="margin:0 0 20px 0;"> <?php echo isset($arrTypes[$type])?$arrTypes[$type]:'Message'?></h4>
<!-- compose message btn -->
<?php echo $this->Html->link('<i class="fa fa-pencil"></i> Compose Message',array('controller'=>'messages','action'=>'compose'), array('class'=>'btn btn-block btn-primary','escape'=>false)); ?>
<!-- Navigation - folders-->
<div style="margin-top: 15px;">
    <ul class="nav nav-pills nav-stacked">
        <li class="header">Folders</li>        
        <li class="<?php echo ($type=='' || $type=='inbox')?'active':''?>">
            <?php echo $this->Html->link($arrTypes['inbox'] . ((isset($inboxUnread) && !empty($inboxUnread))?'('.$inboxUnread.')':''),array('action'=>'index'),array('escape'=>false))?>
        </li>
        <li class="<?php echo ($type=='sent')?'active':''?>">
            <?php echo $this->Html->link($arrTypes['sent'].((isset($sentUnread)&& !empty($sentUnread))?'('.$sentUnread.')':''),array('action'=>'index','sent'),array('escape'=>false))?>
        </li>
        <li class="<?php echo ($type=='trash')?'active':''?>">
            <?php echo $this->Html->link($arrTypes['trash'].((isset($trashUnread)&& !empty($trashUnread))?'('.$trashUnread.')':''),array('action'=>'index','trash'),array('escape'=>false))?>
        </li>
    </ul>
</div>
