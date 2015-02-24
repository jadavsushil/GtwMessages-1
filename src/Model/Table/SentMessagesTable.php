<?php


namespace GtwMessage\Model\Table;

use Cake\ORM\Table;
use Cake\I18n\Time;

class SentMessagesTable extends Table {

    public function initialize(array $config) {
        $this->belongsTo('Sender', [
            'className' => 'GintonicCMS.Users',
            'foreignKey' => 'user_id',
            'propertyName' => 'Sender',
            'fields' => array('id', 'first', 'last', 'email')
        ]);

        $this->belongsTo('Receiver', [
            'className' => 'GintonicCMS.Users',
            'foreignKey' => 'recipient_id',
            'propertyName' => 'Receiver',
            'fields' => array('id', 'first', 'last', 'email')
        ]);
        $this->primaryKey('id');
        
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
        
    public function savesentmessage($data = null){
        if(!empty($data)){
            $sentMessageEntity = $this->newEntity($data);
            $this->save($sentMessageEntity);
        }
    }
    

    function setRead($message) {
        if (empty($message->is_read) || $message->read_on_date == '0000-00-00 00:00:00') {
            $this->updateAll(['is_read' => 1, 'read_on_date' => date("Y-m-d H:i:s")], ['id' => $message->id]);
        }
    }

}
