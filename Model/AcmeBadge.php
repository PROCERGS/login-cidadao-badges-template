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
