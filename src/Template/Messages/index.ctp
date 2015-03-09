<?php
use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;
use Cake\View\Helper\PaginatorHelper;

$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('message/messages');
?>
<span class="gtw-message">
    <h1><?php echo __('Messages'); ?></h1>
    <div class="row">
        <?php echo $this->element('GtwMessage.header');?>
        <div class="col-md-12  col-xs-12">
            <?php echo $this->Form->create('morefuntion', ['id' => 'morefunid', 'class' => 'form-horizontal']); ?>
            <?php echo $this->Form->input('type', array('type' => 'hidden', 'id' => 'gtwMessagetype', 'value' => $type)); ?>
            <?php if (!empty(count($messages))) { ?>
            <div class="row pad">
                <div class="col-sm-6">
                    <label>
                        <input type="checkbox" style="margin-right: 10px;" id="check-all"/>
                    </label>
                    <!-- Action button -->
                    <div class="btn-group">
                        <button type="button" class="more-action disabled btn btn-default btn-sm btn-flat dropdown-toggle" data-toggle="dropdown">
                            More Actions <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" data-url="<?php echo Router::url(array('controller' => 'messages', 'action' => 'multiple_action')); ?>">
                            <?php if ($type == 'inbox') { ?>
                                <li><a href="#" data-value='read' class="preventDefault">Mark as read</a></li>
                                <li><a href="#" data-value='unread' class="preventDefault">Mark as unread</a></li>
                                <li class="divider"></li>
                            <?php } ?>
                            <li><a href="#" data-value='delete' class="preventDefault">Delete</a></li>
                        </ul>
                    </div>

                </div>                   
            </div>
            <?php } ?>
            <div class="table-responsive">
                <?php if (empty(count($messages))) { ?>
                    <div class='text-warning'><?php echo __('No message.') ?></div>
                    <?php
                } else {                
                    ?>
                    <table class="table table-mailbox" id="inbox-table" style="margin-bottom: 0">
                        <?php foreach ($messages as $message) { ?>
                            <tr class="<?php echo (!$message->is_read && $type == 'inbox') ? 'unread' : '' ?>">
                                <td class="small-col" width="2%">
                                    <input type="checkbox" name="data[morefuntion][chk]" id="<?php echo $message->id ?>" value="<?php echo $message->id ?>" class="chkcls more-action-chk">
                                </td>
                                <td class="small-action">
                                    <?php
                                    if ($type == "trash" || $type == "sent") {
                                        echo $this->Html->link('<i class="fa fa-trash-o"> </i>', array('controller' => 'messages', 'action' => 'delete', $message->id, $type), array('escape' => false, 'title' => 'Delete this message'), 'Are you sure? You want to delete this message permanently.');
                                    } else {
                                        echo $this->Html->link('<i class="fa fa-mail-reply"> </i>', array('controller' => 'messages', 'action' => 'reply', $message->id, $type), array('escape' => false, 'title' => 'Reply this message', 'class' => 'navigation'));
                                        echo '&nbsp|&nbsp';
                                        echo $this->Html->link('<i class="fa fa-trash-o"> </i>', array('controller' => 'messages', 'action' => 'delete', $message->id, $type), array('escape' => false, 'title' => 'Delete this message'), 'Are you sure? You want to delete this message.');
                                    }
                                    ?>
                                </td>
                                <td class="inbox-data-from hidden-xs hidden-sm name">
                                    <div>
                                        <?php
                                        if ($type == 'sent') {
                                            echo $message->Receiver->first . ' ' . $message->Receiver->last;
                                        } else {
                                            echo $message->Sender->first . ' ' . $message->Sender->last;
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td class="inbox-data-message subject">
                                    <div>
                                        <span>
                                            <?php echo $this->Html->link($message->title, array('controller' => 'messages', 'action' => 'view', $message->id, $type), array('title' => 'Click here to View Message', 'class' => 'navigation')); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="col-md-2 col-lg-2 col-sm-3 hidden-xs time">
                                    <div>
                                        <?php echo $this->Time->timeAgoInWords($message['created']); ?>
                                    </div>
                                </td>
                                
                            </tr>
                        <?php } ?>                                                            
                    </table>
                    <div class="pull-right pagination">
                        <small class="pull-right">
                            <span><?php echo $this->Paginator->counter('Showing {{start}} - {{end}}/{{count}}'); ?></span>
                            <span class="pull-right" style="list-style: none">
                                <?php
                                if ($this->Paginator->hasPage()) {
                                    echo $this->Paginator->prev('<i class="fa fa-caret-left btn btn-xs btn-primary"></i>',['escape'=>false]);
                                    echo $this->Paginator->next('<i class="fa fa-caret-right btn btn-xs btn-primary"></i>',['escape'=>false]);
                                }
                                ?>
                            </span>
                            </small>
                        </div>
                <?php } ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</span>
