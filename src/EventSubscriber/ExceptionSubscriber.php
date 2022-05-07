<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
       return [
           ExceptionEvent::class => ["checkException", 10]
       ];
    }

    public function checkException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if (
            !$exception instanceof BadRequestHttpException
            && !$exception instanceof InvalidCsrfTokenException
        ) {
            return null;
        }

        $message = $exception->getMessage();
        if (!$message) {
            return null;
        }

        $event->setResponse(new Response($message));
    }
}
