<?php

namespace GtwMessage\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;

class MessagesController extends AppController {

    public $uses = array('GintonicCMS.Users', 'GtwMessage.Messages', 'GtwMessage.SentMessages', 'GtwMessage.TrashMessages');
    public $helpers = ['GintonicCMS.GtwRequire', 'GintonicCMS.Custom', 'Paginator'];
    
    public $paginate = ['maxLimit' => 5];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Auth');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        if(Plugin::loaded('GintonicCMS')){
            $this->layout = 'GintonicCMS.default';
            if($this->request->session()->read('Auth.User.role') == 'admin'){
                $this->layout = 'GintonicCMS.admin';    
            }
        }
        if($this->request->session()->read('Auth.User.id')){
            $this->getUnreadMessage($this->request->session()->read('Auth.User.id'));
        }
    }
    
    function getUnreadMessage($userId = null){
        if(!empty($userId)){
            $this->loadModel('GtwMessage.SentMessages');
            $this->loadModel('GtwMessage.TrashMessages');
            $query = $this->Messages->find('all')
                                                ->where(['recipient_id'=>$userId,'is_read'=>0]);
            $inboxUnread  = $query->count();
//            $query = $this->SentMessages->find('all')
//                                                ->where(['user_id'=>$userId]);
//            $sentUnread  = $query->count();
//            $query = $this->TrashMessages->find('all')
//                                                ->where(['user_id'=>$userId]);
//            $trashUnread  = $query->count();
//            $this->set(compact('trashUnread','sentUnread','inboxUnread'));
            $this->set(compact('inboxUnread'));
        }
    }

    public function index($type = 'inbox') {
        $userId = $this->request->session()->read('Auth.User.id');
        $this->set('title_for_layout', 'Messages');
        $response = $this->Messages->getMessages($type, $userId);
        $model = $response['model'];
        $this->loadModel('GtwMessage.' . $model);
        $query = $this->{$model}->find('all',['order'=>$response['conditions']['order']])->where($response['conditions']['conditions'])->contain($response['conditions']['contain']);
        $messages = $this->paginate($query);
        $this->set(compact('type', 'model', 'messages'));
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

    public function compose() {
        $this->set('title_for_layout', 'Compose Message');
        $userId = $this->request->session()->read('Auth.User.id');
        if (!empty($this->request->data)) {
            $response = $this->Messages->process($this->request->data, 'compose', null, null, $userId);
            if ($response['status']) {
                $this->Flash->success($response['message']);
            } else {
                $this->Flash->error($response['message']);
            }
            echo json_encode($response);
            exit;
        }
        $this->set('messageType', 'compose');
        $this->_setUser();
    }

    public function delete($messageId, $type = null) {
        $response = $this->Messages->deleteMessage($type, $messageId);
        if ($response['status']) {
            $this->Flash->success($response['message']);
        } else {
            $this->Flash->error($response['message']);
        }
        $this->redirect($this->referer());
    }

    public function forward($id = null, $type) {
        
        if (!empty($this->request->data)) {
            $userId = $this->request->session()->read('Auth.User.id');
            $response = $this->Messages->process($this->request->data, 'forward', $id, $type, $userId);
            if ($response['status']) {
                $this->Flash->success($response['message']);
            } else {
                $this->Flash->error($response['message']);
            }
            echo json_encode($response,JSON_NUMERIC_CHECK);
            exit;
        }
        $model = 'Messages';
        if (strtolower($type) == 'sent') {
            $model = 'SentMessages';
        } elseif (strtolower($type) == 'trash') {
            $model = 'TrashMessages';
        }
        $this->loadModel('GtwMessage.' . $model);
        $message = $this->{$model}->find()
                ->where([$model . '.id' => $id])
                ->contain(['Sender', 'Receiver'])
                ->first();
        if (empty($message)) {
            $this->redirect(array('action' => 'index', $type));
        }
        $this->set('message', $message);
        $this->_setUser();
        $this->set('messageType', 'forward');
        $this->render('compose');
    }

    public function view($id = null, $type = 'inbox') {
        $model = 'Messages';
        $message = array();

        if ($type == 'sent') {
            $model = 'SentMessages';
        } else if ($type == 'trash') {
            $model = 'TrashMessages';
        }
        $this->loadModel('GtwMessage.' . $model);
        $message = $this->{$model}->find()
                ->where([$model . '.id' => $id])
                ->contain(['Sender', 'Receiver'])
                ->first();
        if (empty($message)) {
            $this->Flash->success('Message not found.');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->{$model}->setRead($message);
        }
        $this->set(compact('message', 'model', 'type'));
    }

    public function reply($id = null, $type) {
        $this->set('title_for_layout', __('Reply: Message'));
        if (!empty($this->request->data)) {
            $userId = $this->request->session()->read('Auth.User.id');
            $response = $this->Messages->process($this->request->data, 'reply', $id, $type, $userId);
            if ($response['status']) {
                $this->Flash->success($response['message']);
            } else {
                $this->Flash->error($response['message']);
            }
            echo json_encode($response, JSON_NUMERIC_CHECK);
            exit;
        }
        $model = 'Messages';
        if (strtolower($type) == 'sent') {
            $model = 'SentMessages';
        } elseif (strtolower($type) == 'trash') {
            $model = 'TrashMessages';
        }
        $this->loadModel('GtwMessage.' . $model);
        $message = $this->{$model}->find()
                ->where([$model . '.id' => $id])
                ->contain(['Sender', 'Receiver'])
                ->first();
        if (empty($message)) {
            $this->redirect(array('action' => 'index', $type));
        }

        $this->set('message', $message);
        $this->_setUser();
        $this->set('messageType', 'reply');
        $this->render('compose');
    }
    
    public function multiple_action($ids = null,$action =null,$type = null){
        $response = $this->Messages->performAction($type, $ids, $action);
        if ($response['status']) {
            $this->Flash->success($response['message']);
        } else {
            $this->Flash->error($response['message']);
        }
        if($this->request->is('ajax')){
            echo json_encode($response);
            exit;
        }else{
            $this->redirect($this->referer());
        }
        exit;
    }
    
    private function _setUser() {
        $user = TableRegistry::get('GintonicCMS.Users');
        $usersObj = $user
                ->find('list', ['idField' => 'id', 'valueField' => 'email'])
                ->where(['id !=' => $this->request->session()->read('Auth.User.id')]);

        $users = array();
        foreach ($usersObj as $key => $user) {
            $users[$key] = $user;
        }
        $this->set(compact('users'));
    }

}

?>
