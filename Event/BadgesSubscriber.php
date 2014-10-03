<?php

namespace Acme\BadgesBundle\Event;

use PROCERGS\LoginCidadao\BadgesBundle\Model\AbstractBadgesEventSubscriber;
use PROCERGS\LoginCidadao\BadgesBundle\Event\EvaluateBadgesEvent;
use Acme\BadgesBundle\Model\AcmeBadge;

class BadgesSubscriber extends AbstractBadgesEventSubscriber
{

    public function __construct()
    {
        $this->registerBadge('random_even', 'A person with this badge had the luck of getting an even number in rand(1,10)');
        $this->setName('acme_badges');
    }

    public function onBadgeEvaluate(EvaluateBadgesEvent $event)
    {
        if (rand(1,10) % 2 === 0) {
            $event->registerBadge(new AcmeBadge($this->getName(), 'random_even', true));
        }
    }

}
