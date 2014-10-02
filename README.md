Badges Template
===============

This project is a template/example for implementing badges compatible with login-cidadao.

## Implementing Your Own Badges

### Bundle Generation
First of all, generate a new Symfony 2 bundle and name it as you see fit. In this example we'll call it "Acme\BadgesBundle".

Go on and start the interactive bundle generator:

``` bash
$ php app/console generate:bundle
```

### Badge Model

We'll need a Model class to represent your badges. This class can be as simple as the implementation of the `PROCERGS\LoginCidadao\BadgesBundle\Model\BadgeInterface`, thus we have:

```php
<?php

namespace Acme\BadgesBundle\Model;

use PROCERGS\LoginCidadao\BadgesBundle\Model\BadgeInterface;

class AcmeBadge implements BadgeInterface
{

    protected $namespace;
    protected $name;
    protected $data;

    public function __construct($namespace, $name, $data = null)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

}
```

### Event Subscriber: where the magic happens

Now that we can represent our badge, let's create the class that will respond the Badge System's events.
Luckly all the boring stuff is already done by the `PROCERGS\LoginCidadao\BadgesBundle\Model\AbstractBadgesEventSubscriber` abstract class. All we have to do is implement the missing methods and handle the event as needed to validate our badge.

The badge validation is done in the `onBadgeEvaluate(EvaluateBadgesEvent)` method and that's where the hard work should be done. This is defined in the `AbstractBadgesEventSubscriber`.

We also need the `getName()` method to tell the Badges System what's the "namespace" of your badges and finally the `getAvailableBadges()` method will tell the application what badges are provided by this bundle.

``` php
<?php

namespace Acme\BadgesBundle\Event;

use PROCERGS\LoginCidadao\BadgesBundle\Model\AbstractBadgesEventSubscriber;
use PROCERGS\LoginCidadao\BadgesBundle\Event\EvaluateBadgesEvent;
use Acme\BadgesBundle\Model\AcmeBadge;

class BadgesSubscriber extends AbstractBadgesEventSubscriber
{

    public function onBadgeEvaluate(EvaluateBadgesEvent $event)
    {
        if (rand(1,10) % 2 === 0) {
            $event->registerBadge(new AcmeBadge($this->getName(), 'random_even', true));
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
```

Note that the structure of the `getAvailableBadges()` is pretty simple and consist of and array containing the "metadata" of the badges in another array indexed by the badge's name. Another example would be:

``` php
return array(
    'my_badge' => array(
        'description' => "Here you type a description telling the users how to achieve the badge or something."
    ),
    'another_badge' => array(
        'description' => "Here goes another description."
    )
);
```

### Almost done: services.yml

Now all we have to do is tell the application we exist by configuring the bundle's services.yml like so:

``` yaml
parameters:
    acme_badges.subscriber.class: Acme\BadgesBundle\Event\BadgesSubscriber

services:
    acme_badges.subscriber:
        class: %acme_badges.subscriber.class%
        tags:
            - { name: kernel.event_subscriber }
```

This tells the application that the service `acme_badges.subscriber` is an event subscriber so it'll get notified when one of it's subscribed events are triggered.

### That's it!

Assuming your bundle is correctly added in `app/AppKernel.php`, all your badges will be automatically detected, no extra configuration needed.

If you wish to trigger the badge evaluation event manually you can:

``` php
use PROCERGS\LoginCidadao\BadgesBundle\BadgesEvents;
use PROCERGS\LoginCidadao\BadgesBundle\Event\EvaluateBadgesEvent;
use PROCERGS\LoginCidadao\BadgesBundle\Event\ListBadgesEvent;

/** @var \PROCERGS\LoginCidadao\CoresBundle\Model\PersonInterface **/
$user = $this->getUser();

/** @var \PROCERGS\LoginCidadao\BadgesBundle\Handler\BadgesHandler **/
$badgesHandler = $this->get('badges.handler');

$badgesHandler->evaluate($user);
var_dump($user->getBadges()); // this may or may not return the badge we created here since it's based on random stuff.

$badges = $badgesHandler->getAvailableBadges();
var_dump($badges); // this will list all available badges
```