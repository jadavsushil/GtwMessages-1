<?php

namespace GtwMessage\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;

class MessagesController extends AppController {
    public $helpers = ['GintonicCMS.GtwRequire', 'GintonicCMS.Custom', 'Paginator'];
    public $paginate = ['maxLimit' => 5];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Auth');
        $this->loadModel('GtwMessage.Threads');
        $this->loadModel('GtwMessage.ThreadParticipants');
        $this->loadModel('GtwMessage.MessageReadStatuses');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->__checklogin();
        if(Plugin::loaded('GintonicCMS')){
            $this->loadModel('GintonicCMS.Users');
            $this->layout = 'GintonicCMS.default';
            if($this->request->session()->read('Auth.User.role') == 'admin'){
                $this->layout = 'GintonicCMS.admin';    
            }
            $this->__setHeader();
        }
    }
    
    public function index($type = 'inbox') {
        $this->set('title_for_layout', 'Messages');
    }
    
    function __setHeader(){
        if($this->request->session()->check('Auth.User.id')){
            $usersList = $this->Users->find()
                                    ->where(['Users.validated'=>1,'NOT'=>['Users.id'=>$this->request->session()->read('Auth.User.id')]])
                                    ->andWhere(['NOT'=>['Users.role'=>'admin']])
                                    ->select(['id','file_id','email','first','last'])
                                    ->contain(['Files'=>function($file){
                                        return $file 
                                                ->select(['id','filename']);
                                    }])
                                    ->toArray();
            $usersList = $this->getUnreadMessage($usersList);
            $this->set(compact('usersList'));
        }
    }

    public function compose($recipientId = null) {
        $recipientId = (int)$recipientId;
        $this->set('title_for_layout', 'Compose Message');
        $userId = $this->request->session()->read('Auth.User.id');
        if (!empty($this->request->data)) {
            $response = $this->Messages->sentMessage($userId,$this->request->data);
            if ($response['status']) {
                $message = ['id'=>$response['id'],'body'=>$this->request->data['body']];
                $this->set(compact('message'));
                $response['content'] = $this->render('GtwMessage.Element/new_message', 'ajax')->body();
                $this->Flash->success($response['message']);
            } else {
                $this->Flash->error($response['message']);
            }
            echo json_encode($response);
            exit;
        }
        $recipient = $this->Users->find()
                                    ->where(['Users.id'=>$recipientId])
                                    ->select(['id','first','last','email'])
                                    ->first()
                                    ->toArray();
        $threadId = $this->Threads->getThread($userId,$recipientId);
        $threadParticipantId = $this->ThreadParticipants->getThreadParticipant($threadId,$userId);
        $threadRecipientId = $this->ThreadParticipants->getThreadParticipant($threadId,$recipientId);
        $chats = $this->Messages->find()
                                ->where(['Messages.thread_id'=>$threadId])
                                ->all()
                                ->toArray();
        $unReadMessage = $this->getUnreadMessage($threadRecipientId,true);
        $threadMessageList = $this->Messages->find()
                                ->where(['Messages.thread_id'=>$threadId])
                                ->combine('id','id')
                                ->toArray();
        $deletedMessage = $this->MessageReadStatuses->find('all')
                                                    ->where(['MessageReadStatuses.status'=>2,'MessageReadStatuses.message_id IN'=>$threadMessageList])
                                                    ->combine('message_id','message_id')
                                                    ->toArray();
        $this->MessageReadStatuses->updateAll(['status'=>1],['message_id IN'=>$unReadMessage]);
        $this->set('messageType', 'compose','recipient');
        $this->set(compact('recipient','threadId','threadParticipantId','chats','unReadMessage','threadRecipientId','deletedMessage'));
    }
    
    function getUnreadMessage($participantId = null,$getUnreadMessageId = false){
        if(!empty($participantId) && !empty($getUnreadMessageId)){
            $query = $this->MessageReadStatuses->find('all')
                                    ->where(['MessageReadStatuses.thread_participant_id'=>$participantId,'MessageReadStatuses.status'=>0])
                                    ->combine('message_id','message_id');
            return $query->toArray();
        }else{
            $userId = $this->request->session()->read('Auth.User.id');
            $userList = $participantId;
            foreach ($participantId as $key=>$user)
            {
                $userList[$key]['unread_message'] = 0;
                $recipantThreads = $this->ThreadParticipants->find()
                                    ->where(['ThreadParticipants.user_id'=>$userId])
                                    ->select(['ThreadParticipants.thread_id'])
                                    ->combine('thread_id','thread_id')
                                    ->toArray();
                $threads = $this->ThreadParticipants->find()
                        ->where(['ThreadParticipants.user_id'=>$user->id,'ThreadParticipants.thread_id IN'=>$recipantThreads])
                        ->select(['ThreadParticipants.id'])
                        ->order('ThreadParticipants.thread_id ASC')
                        ->toArray();
                if(!empty($threads)){
                    $threadparticipantId = $threads[0]['id'];
                    $userList[$key]['unread_message'] =  $this->MessageReadStatuses->find('all')
                                    ->where(['MessageReadStatuses.thread_participant_id'=>$threadparticipantId,'MessageReadStatuses.status'=>0])
                                    ->combine('message_id','message_id')
                                    ->count();
                }
            }
            return $userList;
        }
    }

    public function delete($messageId = 0) 
    {
        $this->layout = 'ajax';
        $response = $this->Messages->changeMessageStatus($messageId,2);
        echo json_encode($response);
        exit;
    }
    
    public function changeStatus($messageId = 0,$status = 0)
    {
        $this->layout = 'ajax';
        $response = $this->Messages->changeMessageStatus($messageId,$status);
        echo json_encode($response);
        exit;
    }
    
    public function groupChat()
    {
        if($this->request->is(['put','post']))
        {
            debug($this->request->data);
            exit;
        }
    }
    
    private function _setUser() {
        $users = $this->Users
                ->find('list', ['idField' => 'id', 'valueField' => 'email'])
                ->where(['id !=' => $this->request->session()->read('Auth.User.id')])
                ->toArray();
        $this->set(compact('users'));
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

}

?>
