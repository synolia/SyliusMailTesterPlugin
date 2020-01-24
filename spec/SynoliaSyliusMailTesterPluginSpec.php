<?php

declare(strict_types=1);

namespace spec\Synolia\SyliusMailTesterPlugin;

use PhpSpec\ObjectBehavior;
use Synolia\SyliusMailTesterPlugin\SynoliaSyliusMailTesterPlugin;

final class SynoliaSyliusMailTesterPluginSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SynoliaSyliusMailTesterPlugin::class);
    }
}
