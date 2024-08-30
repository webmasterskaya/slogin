<?php

namespace Joomla\Component\SLogin\Administrator\Event;

use BadMethodCallException;
use Joomla\CMS\Event\AbstractImmutableEvent;

class DeleteUserEvent extends AbstractImmutableEvent
{
    public function __construct(string $name, array $arguments = [])
    {

        if (!\array_key_exists('id', $arguments))
        {
            throw new BadMethodCallException("Argument 'id' of event $name is not of the expected type");
        }

        parent::__construct($name, $arguments);
    }
}
