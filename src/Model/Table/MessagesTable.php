<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

namespace GtwMessage\Model\Table;

use Cake\Routing\Router;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\I18n\Time;

class MessagesTable extends Table {

    public $uses = array('GtwMessage.SentMessages');

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
        
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always'
                ]
            ]
        ]);
    }

    public function validationDefault(Validator $validator) {
        return $validator
                        ->notEmpty('recipient_id', 'Please select recipient')
                        ->notEmpty('title', 'Please enter subject');
    }

    function setRead($message) {
        if (empty($message->is_read) || $message->read_on_date == '0000-00-00 00:00:00') {
            $this->updateAll(['Messages.is_read' => 1, 'Messages.read_on_date' => date("Y-m-d H:i:s")], ['Messages.id' => $message->id]);
        }
    }

    public function getMessages($type, $user_id) {
        //Validate Type
        if (!in_array($type, array('inbox', 'trash', 'sent'))) {
            $type = 'inbox';
        }
        $model = "Messages";

        $arrConditions = array();
        if ($type == 'sent') { //Sent Item
            $model = "SentMessages";
            $arrConditions['user_id'] = $user_id;
        } else if ($type == 'trash') { // Trash Item
            $model = "TrashMessages";
            $arrConditions['OR'] = array(
                array('AND' => array('recipient_id' => $user_id, 'deleted_by' => 'inbox')),
                array('AND' => array('user_id' => $user_id, 'deleted_by' => 'sent')),
            );
        } else { //Inbox
            $arrConditions['recipient_id'] = $user_id;
        }

        $response['conditions'] = [
            'conditions' => $arrConditions,
            'order' => [
                'created' => 'asc'
            ],
            'contain' => ['Sender', 'Receiver']
        ];
        $response['model'] = $model;
        return $response;
    }

    public function process($data, $model, $id = null, $type = null, $userId) {
        if (!empty($data)) {

            $response['redirect'] = Router::url(array('controller' => 'messages', 'action' => 'compose'));
            $response['message'] = __('Unable to send your message.');
            $response['status'] = false;

            $data['user_id'] = $userId;
            $sentData = $data;
            if ($model != 'compose') {
                $data['response_to_id'] = $id;
                $response['redirect'] = Router::url(array('controller' => 'messages', 'action' => 'index', $type));
            }
            $message = $this->newEntity($data);
            if ($result = $this->save($message)) {
                $sentData['created'] = $sentData['modified'] = date('Y-m-d H:i:s');
                $SentMessages = TableRegistry::get('SentMessages');
                $sendMessage = $SentMessages->newEntity($sentData);
                $result = $SentMessages->save($sendMessage);
                $response = $this->__setSuccess($type, __('Your Message has been sent successfully.'));
            }
            return $response;
        }
    }

    public function performAction($type, $ids, $action) {
        $response['redirect'] = Router::url(array('controller' => 'messages', 'action' => 'index', $type));
        $response['message'] = __('Unable to complete action. Please try agian !!!');
        $response['status'] = false;
        if (in_array($type, array('inbox', 'sent', 'trash'))) {
            $this->SentMessages = TableRegistry::get('SentMessages');
            $this->TrashMessages = TableRegistry::get('TrashMessages');

            $model = 'Messages';
            if ($type == 'trash') {
                $model = 'TrashMessages';
            } elseif ($type == 'sent') {
                $model = 'SentMessages';
            }
            $messageIdArr = explode(",", $ids);
            $messageIdArr = array_filter($messageIdArr, "is_numeric");
            if ($action == 'delete') {
                if ($type == 'trash') {
                    // Message deleted
                    if ($this->TrashMessages->deleteAll(['id IN' => $messageIdArr])) {
                        $response = $this->__setSuccess($type);
                    }
                } else {
                    for ($i = 0; $i < count($messageIdArr); $i++) {
                        $messageId = $messageIdArr[$i];
                        if (!empty($messageId) && is_numeric($messageId)) {
                            $this->deleteMessage($type, $messageId);
                        }
                    }
                    $response = $this->__setSuccess($type);
                }
            } else if ($action == 'read') { // Mark as read
                if ($model == 'Messages') {
                    if ($this->updateAll(['is_read' => 1], ['id IN' => $messageIdArr])) {
                        $response = $this->__setSuccess($type);
                    }
                }
            } else if ($action == 'unread') { // Mark as unread
                if ($model == 'Messages') {
                    if ($this->updateAll(['is_read' => 0], ['id IN' => $messageIdArr])) {
                        $response = $this->__setSuccess($type);
                    }
                }
            }
        }
        return $response;
    }

    public function deleteMessage($type = null, $messageId = null) {
        $response = array(
            'status' => false,
            'message' => __('Unable to delete message, Please try again')
        );
        if (in_array($type, array('inbox', 'sent', 'trash'))) {
            $this->TrashMessages = TableRegistry::get('GtwMessage.TrashMessages');
            $this->SentMessage = TableRegistry::get('GtwMessage.SentMessages');
            if ($type == 'trash') {
                $trashMessage = $this->TrashMessages->get($messageId);
                if ($this->TrashMessages->delete($trashMessage)) {
                    $response = $this->__setSuccess($type, __('Message has been deleted successfully'));
                }
            } else {
                $model = ($type == 'sent') ? 'SentMessages' : 'Messages';
                $message = ($type == 'sent') ? $this->SentMessage->get($messageId) : $this->get($messageId);
                if (!empty($message)) {
                    //$this->TrashMessage->create();
                    $data['message_id'] = $message->id;
                    $data['user_id'] = $message->user_id;
                    $data['recipient_id'] = $message->recipient_id;
                    $data['title'] = $message->title;
                    $data['body'] = $message->body;
                    $data['is_read'] = $message->is_read;
                    $data['read_on_date'] = $message->read_on_date;
                    $data['response_to_id'] = $message->response_to_id;
                    $data['deleted_by'] = $type;

                    $trashEntity = $this->TrashMessages->newEntity($data);
                    $this->TrashMessages->save($trashEntity);
                    if (($type == 'sent') ? $this->SentMessage->delete($message) : $this->delete($message)) {
                        $response = $this->__setSuccess($type, __('Message has been deleted successfully'));
                    }
                }
            }
        }
        return $response;
    }

    private function __setSuccess($type = null, $msg = null) {
        $response['redirect'] = empty($type) ? Router::url(array('controller' => 'messages', 'action' => 'index')) : Router::url(array('controller' => 'messages', 'action' => 'index', $type));
        $response['message'] = !empty($msg) ? $msg : __('Action has been completed successfully');
        $response['status'] = true;

        return $response;
    }

}
