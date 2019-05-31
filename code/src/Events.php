<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/21/19
 * Time: 2:39 AM
 */

namespace App;


use App\EventSubscribers\ExceptionSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Events
{
    public static function register(EventDispatcher $eventDispatcher)
    {
        $eventDispatcher->addSubscriber(new ExceptionSubscriber());
    }

}