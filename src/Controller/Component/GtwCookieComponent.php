<?php

namespace GintonicCMS\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class GtwCookieComponent extends Component {
    
    public $components = array('Cookie','Auth');
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->Cookie->key = Configure::read('GtwCookie.key');
        $this->Cookie->name = Configure::read('GtwCookie.name');
        $this->Cookie->httpOnly = true;
        //$this->Controller = $controller;
    }
    
    public function autoAuth(){
        if(!$this->Cookie->read('remember_me') || $this->Auth->loggedIn()){
            return;
        }
        $user = $this->Cookie->read('remember_me');
        if(empty($this->Auth->user())){
            //$this->Controller->redirect(array('plugin' => 'GtwUsers', 'controller' => 'users', 'action' => 'signout' ));
            $this->Controller->redirect(array('controller' => 'users', 'action' => 'signout'));
        }
        return $this->Controller->redirect($this->Auth->redirectUrl());
    }
    
    public function rememberMe($userInfo){
        $this->Cookie->write('remember_me', $userInfo, true, Configure::read('GtwCookie.loginDuration'));
    }
    public function forgetMe(){
        $this->Cookie->delete('remember_me');
    }
    
}