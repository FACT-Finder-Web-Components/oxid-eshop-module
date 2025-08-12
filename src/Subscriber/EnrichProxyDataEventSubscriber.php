<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use Omikron\FactFinder\Oxid\Event\EnrichProxyDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EnrichProxyDataEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [EnrichProxyDataEvent::class => 'enrichData'];
    }

    public function enrichData(EnrichProxyDataEvent $event): void
    {
        $data                 = $event->getData();
        $data['example_data'] = [
            'some_data'  => 'data_1',
            'some_data2' => 'data_2',
        ];
        $event->setData($data);
    }
}
