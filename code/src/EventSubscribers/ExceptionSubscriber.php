<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/21/19
 * Time: 2:19 AM
 */

namespace App\EventSubscribers;

use App\Exceptions\NotPermittedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 0],
            ],
        ];
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof NotFoundHttpException)
            $event->setResponse(Response::create("page not found", 404));
        else if ($event->getException() instanceof NotPermittedException)
            $event->setResponse(Response::create("not permitted"));
        else if ($event->getException() instanceof \PDOException)
            $event->setResponse(Response::create($event->getException()->getMessage()));
    }
}
