<?php


namespace GtwMessage\Model\Table;

use Cake\Routing\Router;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class MessagesTable extends Table {

    public $uses = array('GtwMessage.SentMessages');

    public function initialize(array $config) {
        $this->belongsTo('Sender', [
            'className' => 'GintonicCMS.Users',
            'foreignKey' => 'user_id',
            'propertyName' => 'Sender',
            'fields' => ['id', 'first', 'last', 'email']
        ]);
        
        $this->belongsTo('Threads', [
            'className' => 'Threads',
            'foreignKey' => 'thread_id',
            'propertyName' => 'Threads'
        ]);
        
        $this->hasOne('MessageReadStatus');
        
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always'
                ]
            ]
        ]);
        parent::initialize($config);
    }

    public function validationDefault(Validator $validator) {
        return $validator
                        ->notEmpty('title', 'Please enter subject')
                        ->notEmpty('body', 'Please enter body');
    }

    function setRead($message) {
        if (empty($message->is_read) || $message->read_on_date == '0000-00-00 00:00:00') {
            $this->updateAll(['Messages.is_read' => 1, 'Messages.read_on_date' => date("Y-m-d H:i:s")], ['Messages.id' => $message->id]);
        }
    }
    
    function sentMessage($userId = null,$reqData = []){
        $response = ['status'=>false,'message'=>'Unable to sent Message','redirect'=>  Router::url(['plugin'=>'GtwMessage','controller'=>'messages','action'=>'compose',$reqData['recipient_id']],true)];
        if(!empty($userId) && !empty($reqData)){
            $parentId = $this->find()
                                    ->where(['Messages.thread_id'=>$reqData['thread_id']])
                                    ->select(['id'])
                                    ->order(['created DESC'])
                                    ->first();
            if(!empty($parentId)){
                $reqData['parent_id']  = $parentId->id;
            }
            if($messageResult = $this->save($this->newEntity($reqData))){
                $this->MessageReadStatuses = TableRegistry::get('MessageReadStatuses');
                $response['id'] = $reqData['message_id'] = $messageResult->id;
                $this->MessageReadStatuses->save($this->MessageReadStatuses->newEntity($reqData));
                $response['status'] = true;
                $response['message'] = 'Message sent successfully.';
            }
        }
        return $response;
    }
    
    function sentGroupMessage($userId = null,$reqData = []){
        $response = ['status'=>false,'message'=>'Unable to sent Message','redirect'=>  Router::url(['plugin'=>'GtwMessage','controller'=>'messages','action'=>'group_chat',$reqData['thread_id']],true)];
        if(!empty($userId) && !empty($reqData)){
            $threadId = $reqData['thread_id'];
            $parentId = $this->find()
                                    ->where(['Messages.thread_id'=>$threadId])
                                    ->select(['id'])
                                    ->order(['created DESC'])
                                    ->first();
            if(!empty($parentId)){
                $reqData['parent_id']  = $parentId->id;
            }
            if($messageResult = $this->save($this->newEntity($reqData))){
                $this->ThreadParticipants = TableRegistry::get('ThreadParticipants');
                $participantUsers = $this->ThreadParticipants->find()
                                        ->where(['ThreadParticipants.thread_id'=>$threadId])
                                        ->combine('user_id','user_id')
                                        ->toArray();
                $this->MessageReadStatuses = TableRegistry::get('MessageReadStatuses');
                $response['id'] = $reqData['message_id'] = $messageResult->id;
                foreach ($participantUsers as $participantUserId){
                    if($userId != $participantUserId){
                        $this->MessageReadStatuses->save($this->MessageReadStatuses->newEntity($reqData));
                    }
                }
                $response['status'] = true;
                $response['message'] = 'Message sent successfully.';
            }
        }
        return $response;
    }
    
    function changeMessageStatus($messageId = null,$status = 0){
        $response = ['status'=>__('fail')];
        if(!empty($messageId)){
            $this->MessageReadStatuses = TableRegistry::get('MessageReadStatuses');
            if($this->MessageReadStatuses->updateAll(['status'=>$status],['message_id'=>$messageId])){
                $response = ['status'=>__('success')];
            }
        }
        return $response;
    }
    
    
}
