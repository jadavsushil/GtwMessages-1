<?php
$type = isset($type)?$type:'';
$arrTypes = array(
                'compose' => '<i class="fa fa-pencil"></i> Compose',
                'inbox' => '<i class="fa fa-inbox"></i> Inbox',
                'sent' => '<i class="fa fa-mail-forward"></i> Sent',
                'trash' => '<i class="fa fa-trash-o"></i> Trash',
                'reply' => '<i class="fa fa-reply"></i> Reply Message',
                'view' => '<i class="fa fa-envelope-o"></i> Message Detail',
                'forward' => '<i class="fa fa-mail-forward"></i> Forward Message',
            );
?>

<h1 class="page-title txt-color-blueDark hidden-tablet">
    <?php echo isset($arrTypes[$type])?$arrTypes[$type]:'Message'?>
</h1>
<div class="btn-group hidden-desktop visible-tablet">
    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <?php echo __('Inbox');?> <i class="fa fa-caret-down"></i>
    </button>
    <ul class="dropdown-menu pull-left">
        <li>
            <?php echo $this->Html->link(__('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('escape'=>false,'class' => 'navigation inbox-load')); ?>
        </li>
        <li>
            <?php echo $this->Html->link(__('Sent'), array('controller' => 'messages', 'action' => 'index','sent', 'admin' => false), array('class' => 'navigation')); ?>
        </li>
        <li>
            <?php echo $this->Html->link(__('Trash'), array('controller' => 'messages', 'action' => 'index','trash', 'admin' => false), array('class' => 'navigation')); ?>
        </li>
    </ul>
</div>
<?php if(!empty($messages)):?>
<div class="inbox-checkbox-triggered">
    <!-- Action button -->
    <label style="margin-left:15px;">
        <input type="checkbox" name="data[morefuntion][chk]"  id="check-all" class="chkcls checkbox style-2"><span style="margin: 0px;"></span> 
    </label>
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-sm btn-flat dropdown-toggle" data-toggle="dropdown">
            More Actions <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" data-url="<?php echo $this->Html->url(array('controller'=>'messages','action'=>'multiple_action'));?>">
            <?php if ($type == 'inbox') { ?>
                <li><a href="#" data-value='read' class="preventDefault">Mark as read</a></li>
                <li><a href="#" data-value='unread' class="preventDefault">Mark as unread</a></li>
                <li class="divider"></li>
            <?php } ?>
            <li><a href="#" data-value='delete' class="preventDefault">Delete</a></li>
        </ul>
    </div>
</div>
<?php endif;?>
<a href="javascript:void(0);" id="compose-mail-mini" class="btn btn-primary pull-left hidden-desktop visible-tablet margin-right-5"> <strong><i class="fa fa-file fa-lg"></i></strong> </a>
