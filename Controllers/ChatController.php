<?php
namespace Controllers;

use DAO\ChatDAO as ChatDAO;
use Models\Chat;
use DateTime;

    class ChatController{
        private $chatDAO;

        public function __construct()
        {
            $this->chatDAO = new ChatDAO();
        }

        public function ShowChatsView(){
            require_once(VIEWS_PATH. "validate-session.php");
            $chatList = $this->chatDAO->GetUserChats($_SESSION["loggedUser"]->getId());
            require_once(VIEWS_PATH. "chats.php");
        }

        public function ShowChatPersonalView($receptor){
            require_once(VIEWS_PATH. "validate-session.php");
            $chatList = $this->chatDAO->GetConversation($_SESSION["loggedUser"]->getId(), $receptor);
            require_once(VIEWS_PATH."chat-personal.php");
        }
        public function ActiveChat($reciever_user_id, $message){
            require_once(VIEWS_PATH. "validate-session.php");
            $chat = new Chat();

            $chat->setMessenger_user_id($_SESSION["loggedUser"]);
            $chat->setReciever_user_id($reciever_user_id);
            $chat->setMessage($message);

            date_default_timezone_set("America/Argentina/Buenos_Aires");

            $date = new DateTime();
            $stringDate = $date->format('Y-m-d H:i:s');
            $timestamp=strtotime($stringDate);
            $dateStamp = date(('Y-m-d H:i:s'), $timestamp);
           

            $chat->setCreated_on($dateStamp);
            $chat->setStatus(1);

            $this->chatDAO->Add($chat);
        }

        public function Add($reciever_user_id, $message){
            require_once(VIEWS_PATH. "validate-session.php");
            $chat = new Chat();

            $chat->setMessenger_user_id($_SESSION["loggedUser"]);
            $chat->setReciever_user_id($reciever_user_id);
            $chat->setMessage($message);

            date_default_timezone_set("America/Argentina/Buenos_Aires");

            $date = new DateTime();
            $stringDate = $date->format('Y-m-d H:i:s');
            $timestamp=strtotime($stringDate);
            $dateStamp = date(('Y-m-d H:i:s'), $timestamp);
           

            $chat->setCreated_on($dateStamp);
            $chat->setStatus(1);

            require_once(VIEWS_PATH."chat-personal.php");
        }



    }
?>