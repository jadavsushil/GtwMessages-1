<?php

use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;
use Cake\View\Helper\PaginatorHelper;

$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('message/messages');
?>
<span class="gtw-message">
    <h1><?php echo __('Messages'); ?></h1>
<?php echo $this->element('GtwMessage.header'); ?>
    <div class="col-md-12  col-xs-12">
        <div class="col-md-7  col-sm-7 message-div">
<?php echo "Select User for chat" ?>
        </div>
    </div>
</span>
