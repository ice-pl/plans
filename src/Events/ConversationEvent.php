<?php

namespace App\Events;

use App\Entity\Conversation;

// use Symfony\Component\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\Event;


class ConversationEvent extends Event
{
	
	public const NAME = 'conversation.opened';
	protected $conversation;

	public function __construct( Conversation $conversation )
	{
		$this->conversation = $conversation;
	}

	public function getConversation()
	{
		return $this->conversation;
	}


}
