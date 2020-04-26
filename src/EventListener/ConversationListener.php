<?php

namespace App\EventListener;

use App\Events\ConversationEvent;

use App\Entity\Conversation;
use Doctrine\ORM\Event\LifecycleEventArgs;


class ConversationListener
{

	public function conversationPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if(!$entity instanceof Conversation)
			return;
	}


	public function conversationOpened(ConversationEvent $conversationEvent){
		// dump('Hello form the new event CONVERSATION EVENT');
		// echo('Hello form the new event CONVERSATION EVENT');
		echo $conversationEvent->getConversation()->getContent();
	}








}