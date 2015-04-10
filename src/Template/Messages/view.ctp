<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
use Cake\I18n\Time;
?>
<span class="messages">
    
    <h1><?php echo __('Messages'); ?></h1>
    <div class="row">        
        <div class="col-md-2 col-xs-3">
            <?php echo $this->element('leftpanel', array('type' => 'view')); ?>
        </div>
        <div class="col-md-10  col-xs-9">
            <div class="row bg-success" style='padding: 10px 0;margin-bottom: 20px;'>
                <div class="col-md-7">
                    <table>
                        <tr>
                            <td class="text-right" style="padding-right:10px;">From :</td>
                            <td><?php echo $message->Sender->first . ' ' . $message->Sender->last . '&nbsp;(' . Time::parse($message->created->i18nFormat())->timeAgoInWords() . ')'; ?></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="padding-right:10px;">Subject :</td>
                            <td><?php echo $message->title; ?></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="padding-right:10px;">To :</td>
                            <td><?php echo $message->Receiver->first . ' ' . $message->Receiver->last; ?></td>
                        </tr>
                    </table>                        
                </div>
                <div class="col-md-5 text-right">

                    <?php
                    echo $this->Html->link('<i class="fa fa-inbox"> </i> Back to ' . $type, array('controller' => 'messages', 'action' => 'index', $type), array('class' => 'btn btn-default', 'escape' => false, 'title' => 'Back to ' . $type,)) . "&nbsp;";
                    if ($type != 'trash') {
                        echo $this->Html->link('<i class="fa fa-mail-reply"> </i> Reply', array('controller' => 'messages', 'action' => 'reply', $message->id, $type), array('class' => 'btn btn-default', 'escape' => false, 'title' => 'Reply this message')) . "&nbsp;";
                        echo $this->Html->link('<i class="fa fa-mail-forward"> </i> Forward', array('controller' => 'messages', 'action' => 'forward', $message->id, $type), array('class' => 'btn btn-default', 'escape' => false, 'title' => 'Forward this message')) . "&nbsp;";
                    }
                    echo $this->Html->link('<i class="fa fa-trash-o"> </i> Delete', array('controller' => 'messages', 'action' => 'delete', $message->id, $type), array('class' => 'btn btn-default', 'escape' => false, 'title' => 'Delete this message'), 'Are you sure? You want to delete this message.');
                    ?>
                    <div class="pad" title="<?php echo $message->created->i18nFormat(); ?>">
                        <?php echo Time::parse($message->created->i18nFormat())->nice(); ?>
                    </div>
                </div>
            </div>        
            <div class="row">
                <div class="col-md-12">
                    <?php echo nl2br($message->body); ?>
                </div>
            </div>
            <p><?php if ($message->is_read) {
                        echo 'Read on: ' . Time::parse($message->read_on_date->i18nFormat())->timeAgoInWords();
                    } ?></p>                
        </div>
    </div>
</span>
