<?php

namespace App\MessageHandler;

use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CommentMessageHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager,
    private CommentRepository $commentRepository)
    {
        
    }

    public function __invoke(CommentMessage $message): void
    {
        $comment = $this ->commentRepository->find($message->getId());
        if(!$comment)
        {
            return;
        }
        $this->entityManager->flush();
    }
}
