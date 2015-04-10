<?php

use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;
use Cake\View\Helper\PaginatorHelper;

$this->Helpers()->load('GintonicCMS.Require');
echo $this->Require->req('message/messages');
?>
<span class="messages">
    <h1><?php echo __('Messages'); ?></h1>
<?php echo $this->element('Messages.header'); ?>
    <div class="col-md-12  col-xs-12">
        <div class="col-md-7  col-sm-7 message-div">
<?php echo "Select User for chat" ?>
        </div>
    </div>
</span>
