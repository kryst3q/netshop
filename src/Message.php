<?php

class Message {
    
    public $id;
    public $title;
    public $senderId;
    public $recipientId;
    public $postDate;
    public $readDate;
    public $message;
    
    public function getId() {
        
        return $this->id;
        
    }

    function getTitle() {
        
        return $this->title;
        
    }

    function setTitle($title) {
        
        return (preg_match('/^[a-z0-9 .\-]+$/i', $title)) ? $this->title = $title : FALSE;
        
    }

        
    public function getSenderId() {
        
        return $this->senderId;
        
    }

    public function getRecipientId() {
        
        return $this->recipientId;
        
    }

    public function getPostDate() {
        
        return $this->postDate;
        
    }

    public function getReadDate() {
        
        return $this->readDate;
        
    }

    public function getMessage() {
        
        return $this->message;
        
    }

    public function setId($id) {
        
        return (is_int($id)) ? $this->id = $id : FALSE;
        
    }

    public function setSenderId($senderId) {
        
        return (is_int($senderId)) ? $this->senderId = $senderId  : FALSE;
        
    }

    public function setRecipientId($recipientId) {
        
        return (is_int($recipientId)) ? $this->recipientId = $recipientId  : FALSE;
        
    }

    function setReadDate($readDate) {
        
        $this->readDate = $readDate;
        
    }

    function setPostDate($postDate) {
        
        $this->postDate = $postDate;
        
    }

        
    public function readMessage() {
        
        $query = "UPDATE messages SET read_date = NOW() WHERE id = " . $this->getId();
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }

    public function setMessage(string $message) {
        
        return (preg_match('/^[a-z0-9 .\-]+$/i', $message)) ? $this->message = $message : FALSE;
        
    }

    public function sendMessage(Message $message) {
        
        $query = "INSERT INTO messages (title, sender_id, recipient_id, post_date, read_date, message) VALUES ('"
                . $message->getTitle() . "', '"
                . $message->getSenderId() . "', '"
                . $message->getRecipientId() . "', NOW(), '', '"
                . $message->getMessage() . ")";
        
        return (Connection::connect($query)) ? TRUE : FALSE;
        
    }
    
    static public function createMessageObject(array $row) {
        
        $message = new Message();
        
        $message->setSenderId($row['sender_id']);
        $message->setTitle($row['title']);
        $message->setRecipientId($row['recipient_id']);
        $message->setPostDate($row['post_date']);
        $message->setReadDate($row['read_date']);
        $message->setMessage($row['message']);
        
        return $message;
        
    }
    
    static public function loadAll($query) {
        
        $result = Connection::connect($query);
        
        if ($result) {
            
            $messages = [];
            
            foreach ($result as $row) {
                
                $messages[] = Message::createMessageObject($row);
                
            }
            
            return $messages;
            
        }
        return FALSE;
        
    }
    
    static public function loadOne($query) {
        
        $result = Connection::connect($query);
        
        if ($result) {
            
            $row = $result->fetch_assoc();
            return Message::createMessageObject($row);
            
        }
        return FALSE;
        
    }
    
    static public function loadMessageById(int $id) {
        
        $query = "SELECT * FROM messages WHERE id = " . $id;
        return Message::loadOne($query);
        
    }
    
    static public function loadAllMessages() {
        
        $query = "SELECT * FROM messages";
        return Message::loadAll($query);
        
    }
    
    static public function loadAllMessagesByRecipientId(int $recipientId) {
        
        $query = "SELECT * FROM messages WHERE recipient_id = " . $recipientId;
        return Message::loadAll($query);
        
    }
    
    static public function loadAllMessagesBySenderId(int $senderId) {
        
        $query = "SELECT * FROM messages WHERE sender_id = " . $senderId;
        return Message::loadAll($query);
        
    }
    
}

