<?php
namespace GtwMessage\Model\Table;

use Cake\ORM\Table;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;

class ThreadParticipantsTable extends Table
{

    public function initialize(array $config) 
    {
        parent::initialize($config);
        $this->primaryKey('id');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new'
                ]
            ]
        ]);
        
        $this->addAssociations([
            'belongsTo' => [
                'Threads' => [
                    'className' => 'GtwMessage.Threads',
                    'foreignKey' => 'thread_id',
                    'propertyName' => 'Thread'
                ],
                'Users' => [
                    'className' => 'GintonicCMS.Users',
                    'foreignKey' => 'user_id',
                    'fields' => ['id', 'first', 'last', 'email'],
                    'propertyName' => 'User'
                ]
            ],
        ]);

        $this->addBehavior('CounterCache', [
            'Threads' => ['thread_participant_count']
        ]);
    }
    
    public function getThreadParticipant($threadId = null,$userId = null,$recipientId = null){
        $userIds = array();
        if(!empty($userId)){
            $userIds[$userId] = $userId;
        }
        if(!empty($recipientId)){
            $userIds[$recipientId] = $recipientId;
        }
        $threadParticipant = $this->find()
                ->where(['thread_id'=>$threadId,'user_id IN'=>$userIds])
                ->first();
        if(!empty($threadParticipant)){
            return $threadParticipant->id;
        }
        return 0;
    }
}
?>