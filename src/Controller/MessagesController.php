<?php

namespace Messages\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class MessagesController extends AppController {
    public $helpers = ['GintonicCMS.Require', 'GintonicCMS.Custom', 'Paginator'];
    public $paginate = ['maxLimit' => 5];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Auth');
        $this->loadModel('Messages.Threads');
        $this->loadModel('Messages.ThreadParticipants');
        $this->loadModel('Messages.MessageReadStatuses');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        if(Plugin::loaded('GintonicCMS')){
            $this->loadModel('GintonicCMS.Users');
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
            $userGroups = $this->Threads->getGroups($this->request->session()->read('Auth.User.id'));
            $this->set(compact('usersList','userGroups'));
        }
    }

    public function compose($recipientId = null,$isGroup = null,$isProhibitUser = null) {
        $this->set('title_for_layout', 'Compose Message');
        if(empty($recipientId) && !empty($isGroup) && $isGroup == 'group'){
            $this->set('isGroupChat',true);
            $this->set('chats',array());
        }else{
            $userId = $this->request->session()->read('Auth.User.id');
            $recipientId = (int)$recipientId;
            if ($this->request->is(['put','post']) && !empty($this->request->data['body']) && empty($isGroup)) {
                $response = $this->Messages->sentMessage($userId,$this->request->data);
                if ($response['status']) {
                    $message = ['id'=>$response['id'],'body'=>$this->request->data['body']];
                    $this->set(compact('message'));
                    $response['content'] = $this->render('Messages.Element/new_message', 'ajax')->body();
                }
                echo json_encode($response);
                exit;
            }
            $recipient = $this->Users->find()
                                        ->where(['Users.id'=>$recipientId])
                                        ->select(['id','first','last','email'])
                                        ->first();
            if(!empty($recipient)){
                $recipient = $recipient->toArray();
            }
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
            if(!empty($unReadMessage)){
                $this->MessageReadStatuses->updateAll(['status'=>1],['message_id IN'=>$unReadMessage]);
            }
            $this->set('messageType', 'compose','recipient');
            $this->set(compact('isProhibitUser','recipientId','recipient','threadId','threadParticipantId','chats','unReadMessage','threadRecipientId','deletedMessage'));
        }
    }
    
    public function group_chat($threadId = null) 
    {
        if(empty($threadId)){
            $this->FlashMessage->setWarning(__('Invalid Group.'));
            return $this->redirect(['plugin'=>'Messages','controller'=>'messages','action'=>'compose']);
        }
        if ($this->request->is(['put', 'post']) && empty($isGroup)) 
        {
            $response = $this->Messages->sentGroupMessage($this->request->session()->read('Auth.User.id'), $this->request->data);
            if ($response['status']) 
            {
                $message = ['id' => $response['id'], 'body' => $this->request->data['body']];
                $this->set(compact('message'));
                $response['content'] = $this->render('Messages.Element/new_message', 'ajax')->body();
            }
            echo json_encode($response);
            exit;
        }
        $groupAdminDetail = $this->Threads->getGroupAdmin($threadId);
        $recipientUsers = $this->ThreadParticipants->getThreadOfUsers($threadId);
        $groupUsersJson = $recipientUsers;
        unset($groupUsersJson[$this->request->session()->read('Auth.User.id')]);
        $groupUsersJson = $this->setUserCommaSepList($groupUsersJson);
        $unReadMessage = $this->getUnreadMessage($this->request->session()->read('Auth.User.id'),true);
        $chats = $this->Messages->find('all')
                                ->where(['Messages.thread_id'=>$threadId]);
        $threadMessageList = $chats->combine('id','id')->toArray();
        $chats = $chats->toArray();
        $deletedMessage = $this->MessageReadStatuses->find('all')
                                                    ->where(['MessageReadStatuses.status'=>2,'MessageReadStatuses.message_id IN'=>$threadMessageList])
                                                    ->combine('message_id','message_id')
                                                    ->toArray();
        $this->set('activeGroupID',$threadId);
        $this->set(compact('recipientUsers','groupUsersJson','chats','groupAdminDetail','deletedMessage','threadId','unReadMessage'));
    }

    public function set_group_chat($activeGroupId = null)
    {
        if($this->request->is(['put','post']))
        {
            $userLists = explode(',', $this->request->data['user_list']);
            if(count($userLists) == 1)
            {
                return $this->redirect(['plugin'=>'Messages','controller'=>'messages','action'=>'compose',$userLists[0]]);
            }
            $threadId = $this->Threads->getThread($this->request->session()->read('Auth.User.id'),0,$userLists);
            return $this->redirect(['plugin'=>'Messages','controller'=>'messages','action'=>'group_chat',$threadId]);
        }
        $this->FlashMessage->setWarning(__('Invalid Group.'));
        return $this->redirect(['plugin'=>'Messages','controller'=>'messages','action'=>'compose']);
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
            if(!empty($participantId)){
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
    
    public function setUserCommaSepList($getUserList = null) {
        $conditions = ['Users.id !=' => $this->request->session()->read('Auth.User.id')];
        if(empty($getUserList)){
            $this->layout="ajax";
            $conditions = ['id !=' => $this->request->session()->read('Auth.User.id'),'email LIKE'=>'%'.$this->request->query['q'].'%'];
        }else{
            $conditions = ['Users.id !=' => $this->request->session()->read('Auth.User.id'),'Users.id IN'=> array_keys($getUserList)];
        }
        $users = $this->Users
                ->find('list', ['keyField' => 'id', 'valueField' => 'email'])
                ->where($conditions)
                ->andWhere(['NOT'=>['Users.role'=>'admin']])
                ->toArray();
        
        
        $userCommaSepList = [];
        foreach ($users as $userId=>$userEmail){
            $userCommaSepList[] = [
                'id' => $userId,
                'name' => $userEmail
            ];
        }
        $jsonData = json_encode($userCommaSepList);
        if(empty($getUserList)){
            echo $jsonData;
            exit;
        }
        return $jsonData;
    }
    
}
?>
