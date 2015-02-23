<div class="inbox-side-bar">
    <?php echo $this->Html->link('<i class="fa fa-pencil"></i> '.__('Compose Message'),array('controller'=>'messages','action'=>'compose'), array('class'=>'btn btn-primary btn-block navigation','escape'=>false)); ?>
    <h6> Folder <!-- <a href="javascript:void(0);" rel="tooltip" title="" data-placement="right" data-original-title="Refresh" class="pull-right txt-color-darken"><i class="fa fa-refresh"></i></a> --></h6>
    <ul class="inbox-menu-lg">    
        <li class="<?php echo ($type=='' || $type=='inbox')?'active':''?>">
            <?php echo $this->Html->link('<i class="fa fa-inbox"></i> '.__('Inbox'),array('action'=>'index'),array('escape'=>false,'class'=>'inbox-load'))?>
        </li>
        <li class="<?php echo ($type=='sent')?'active':''?>">
            <?php echo $this->Html->link('<i class="fa fa-mail-forward"></i> '.__('Sent'),array('action'=>'index','sent'),array('escape'=>false))?>
        </li>
        <li class="<?php echo ($type=='trash')?'active':''?>">
            <?php echo $this->Html->link('<i class="fa fa-trash-o"></i> '.__('Trash'),array('action'=>'index','trash'),array('escape'=>false))?>
        </li>
    </ul>
</div>
<?php echo $this->Form->input('type', array('type' => 'hidden', 'id' => 'gtwMessagetype', 'value' => $type)); ?>
