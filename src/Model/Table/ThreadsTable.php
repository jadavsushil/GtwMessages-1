<?php

namespace GtwMessage\Model\Table;

use Cake\ORM\Table;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class ThreadsTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->primaryKey('id');
        
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always'
                ]
            ]
        ]);
        
        $this->addAssociations([
            'belongsTo' => ['Users'=>[
                'className' => 'GintonicCMS.Users',
                'foreignKey' => 'user_id',
                'propertyName' => 'user_thread'
            ]],
            'hasMany' => ['ThreadParticipants'=>[
                'className' => 'GtwMessage.ThreadParticipants',
                'propertyName'=>'thread_participants'
            ]]
        ]);
    }
        
    function getThread($userId = null,$recipientId = null,$threadUserIds = []){
        $this->ThreadParticipants = TableRegistry::get('GtwMessage.ThreadParticipants');
        $recipantThreads = $this->ThreadParticipants->find()
                                    ->where(['ThreadParticipants.user_id'=>$recipientId])
                                    ->select(['ThreadParticipants.thread_id'])
                                    ->combine('thread_id','thread_id')
                                    ->toArray();
        $threads = $this->ThreadParticipants->find()
                ->where(['ThreadParticipants.user_id'=>$userId,'ThreadParticipants.thread_id IN'=>$recipantThreads])
                ->select(['ThreadParticipants.thread_id'])
                ->order('ThreadParticipants.thread_id ASC')
                ->toArray();
        if(empty($threads)){
            $data['user_id'] = $userId;
            $threadResult = $this->save($this->newEntity($data));
            $data = [];
            $data['thread_id'] = $threadId = $threadResult->id;
            $participantsUsers = [$userId,$recipientId];
            foreach($participantsUsers as $threadUserId){
                $data['user_id'] = $threadUserId;
                $threads[] = $this->ThreadParticipants->save($this->ThreadParticipants->newEntity($data));
            }
        }else{
            $threadId = $threads[0]['thread_id'];
        }
        return $threadId;
    }
    
    
    function setRead($message) {
        if (empty($message->is_read) || $message->read_on_date == '0000-00-00 00:00:00') {
            $this->updateAll(['is_read' => 1, 'read_on_date' => date("Y-m-d H:i:s")], ['id' => $message->id]);
        }
    }

}
?>