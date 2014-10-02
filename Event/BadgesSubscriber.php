<?php

namespace Acme\BadgesBundle\Event;

use PROCERGS\LoginCidadao\BadgesBundle\Model\AbstractBadgesEventSubscriber;
use PROCERGS\LoginCidadao\BadgesBundle\Event\EvaluateBadgesEvent;

class BadgesSubscriber extends AbstractBadgesEventSubscriber
{

    public function onBadgeEvaluate(EvaluateBadgesEvent $event)
    {
        if (rand(1,10) % 2 === 0) {
            $event->registerBadge(new Model\AcmeBadge($this->getName(), 'random_even', true));
        }
    }

    public function getName()
    {
        return 'acme_badges';
    }

    public function getAvailableBadges()
    {
        return array(
            'random_even' => array(
                'description' => 'A person with this badge had the luck of getting an even number in rand(1,10)'
            )
        );
    }

}
