<?php

namespace GtwMessage\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;

class ThreadsController extends AppController {
    
    public $helpers = ['GintonicCMS.GtwRequire', 'GintonicCMS.Custom', 'Paginator'];
    public $paginate = ['maxLimit' => 5];

    public function initialize() {
        parent::initialize();
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }
    
}

?>
