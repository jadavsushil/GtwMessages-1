<?php

namespace GtwMessage\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;

class ThreadParticipantsController extends AppController {
    
    public $helpers = ['GintonicCMS.GtwRequire', 'GintonicCMS.Custom', 'Paginator'];
    public $paginate = ['maxLimit' => 5];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Auth');
        $this->loadModel('Threads');
        $this->loadModel('GintonicCMS.Users');
        $this->loadModel('ThreadParticipants');
        $this->__setHeader();
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        if(Plugin::loaded('GintonicCMS')){
            $this->loadModel('GintonicCMS.Users');
            $this->layout = 'GintonicCMS.default';
            if($this->request->session()->read('Auth.User.role') == 'admin'){
                $this->layout = 'GintonicCMS.admin';    
            }
        }
    }
    
}

?>
