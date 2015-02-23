<?php

namespace GtwMessage\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController {

    function initialize() {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('GtwCookie');
        $this->loadComponent('Auth');

        // Allow the display action so our pages controller
        // continues to work.
        $this->Auth->allow(['display']);
    }

    function isAuthorized($user) {
        if (!empty($user)) {
            if ($user['role'] == 'admin') {
                $this->layout = 'admin';
            }
            return true;
        } else {
            return false;
        }
    }

    function __checklogin() {
        $user = $this->Auth->user();
        $this->layout = 'default';
        if (!empty($user)) {
            if ($user['role'] == 'admin') {
                $this->layout = 'admin';
            }
        }
    }

}
