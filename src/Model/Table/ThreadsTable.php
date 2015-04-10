<?php

namespace Messages\Model\Table;

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
                'className' => 'Messages.ThreadParticipants',
                'propertyName'=>'thread_participants'
            ]]
        ]);
    }
        
    function getThread($userId = null,$recipientId = null,$threadUserIds = []){
        $this->ThreadParticipants = TableRegistry::get('Messages.ThreadParticipants');
        $participantsUsers = [$userId,$recipientId];
        if(empty($recipientId) && !empty($threadUserIds)){
            $threadUserIds[] = $userId;
            $participantsUsers = $threadUserIds;
        }
        $threadQuery = $this->ThreadParticipants->find()
                ->where(['ThreadParticipants.user_id IN' => $participantsUsers]);

        $threads = $threadQuery->select([
                    'ThreadParticipants.thread_id',
                    'count' => $threadQuery->func()->count('ThreadParticipants.thread_id')
                ])
                ->group('ThreadParticipants.thread_id')
                ->having(['count' => count($participantsUsers)])
                ->toArray();
//        $recipantThreads = $this->ThreadParticipants->find()
//                                    ->where(['ThreadParticipants.user_id'=>$recipientId])
//                                    ->select(['ThreadParticipants.thread_id'])
//                                    ->combine('thread_id','thread_id')
//                                    ->toArray();
//        $threads = $this->ThreadParticipants->find()
//                ->where(['ThreadParticipants.user_id'=>$userId,'ThreadParticipants.thread_id IN'=>$recipantThreads])
//                ->select(['ThreadParticipants.thread_id'])
//                ->order('ThreadParticipants.thread_id ASC')
//                ->toArray();
        if(empty($threads) && empty($recipientId) && empty($threadUserIds)){
            return 0;
        }
        if(empty($threads)){
            $data['user_id'] = $userId;
            $threadResult = $this->save($this->newEntity($data));
            $data = [];
            $data['thread_id'] = $threadId = $threadResult->id;
            foreach($participantsUsers as $threadUserId){
                $data['user_id'] = $threadUserId;
                $threads[] = $this->ThreadParticipants->save($this->ThreadParticipants->newEntity($data));
            }
        }else{
            $threadId = $threads[0]['thread_id'];
        }
        return $threadId;
    }
    
    function getGroups($userId = null){
        $threads = $this->find('list',['keyField'=>'id','valueField'=>'id'])
                ->where(['Threads.thread_participant_count >'=>2])
                ->toArray();
        $this->ThreadParticipants = TableRegistry::get('Messages.ThreadParticipants');
        $threadIds = $this->ThreadParticipants->find('list',['keyField'=>'thread_id','valueField'=>'thread_id'])
                                ->where(['ThreadParticipants.thread_id IN'=>$threads,'ThreadParticipants.user_id'=>$userId])
                                ->toArray();
        $groups = array();
        $count = 0;
        foreach ($threadIds as $threadId){
            $groups[$threadId] = 'Group-'.$count++;
        }
        return $groups;
    }
    
    function getGroupAdmin($threadId = null){
        $userData = $this->find()
                ->where(['Threads.id'=>$threadId])
                ->contain(['Users'=>function($userQuery){
                        return $userQuery   
                                ->select(['id','first','last','email']);
                }])
                ->first()
                ->toArray();
        return $userData['user_thread'];
    }
    
    

}
?>