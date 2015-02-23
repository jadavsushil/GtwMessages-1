<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;

$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('message/messages');
?>
<h1><?php echo __('Messages'); ?></h1>
<div class="row">        
    <div class="col-md-2 col-xs-3">
        <?php echo $this->element('leftpanel'); ?>
    </div>
    <div class="col-md-10  col-xs-9">		
        <?php echo $this->Form->create('morefuntion', array('id' => 'morefunid', 'class' => 'form-horizontal')); ?>
        <?php echo $this->Form->input('type', array('type' => 'hidden', 'id' => 'gtwMessagetype', 'value' => $type)); ?>
        <?php if (!empty($messages)) { ?>
            <div class="row pad">
                <div class="col-sm-6">
                    <div class='pagination'>
                        <label style="margin-right: 10px;">
                            <input type="checkbox" id="check-all"/>
                        </label>
                        <!-- Action button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm btn-flat dropdown-toggle" data-toggle="dropdown">
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
                <div class="col-sm-6 text-right">					
                    <ul class="pagination">
                        <li>
                            <a><?php
                                echo $this->Paginator->counter('Displaying {{start}} - {{end}} of {{count}} total');
                                ?></a>
                        </li>
                        <?php
                        echo $this->Paginator->prev('&laquo; Previous', array('escape'=>false,'tag'=>'li'), null, array('escape'=>false,'tag'=>'li','disabledTag'=>'a'));
            //        echo $this->Paginator->numbers(array('separator' => '','tag'=>'li','currentTag'=>'a','currentClass'=>'active'));
                        echo $this->Paginator->next('Next &raquo;', array('escape'=>false,'tag'=>'li'), null, array('escape'=>false,'tag'=>'li','disabledTag'=>'a'));
                        ?>
                    </ul>
                </div>                    
            </div>
        <?php } ?>
        <div class="table-responsive">
            <?php if (empty($messages)) { ?>
                <div class='text-warning'><?php echo __('No message.') ?></div>
                <?php
            } else {                
                ?>
                <table class="table table-mailbox">
                    <?php foreach ($messages as $message) { ?>
                        <tr class="<?php echo (!$message->is_read && $type == 'inbox') ? 'unread' : '' ?>">
                            <td class="small-col">
                                <input type="checkbox" name="data[morefuntion][chk]" id="<?php echo $message->id ?>" value="<?php echo $message->id ?>" class="chkcls">
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
                            <td class="inbox-data-from hidden-xs hidden-sm">
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
                            <td class="inbox-data-message">
                                <div>
                                    <span>
                                        <?php echo $this->Html->link($message->title, array('controller' => 'messages', 'action' => 'view', $message->id, $type), array('title' => 'Click here to View Message', 'class' => 'navigation')); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="col-md-2 col-lg-2 col-sm-3 hidden-xs">
                                <div>
                                    <?php echo $this->Time->timeAgoInWords($message[$model]['created']); ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>                                                            
                </table>
            <?php } ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
