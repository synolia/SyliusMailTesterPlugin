<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        /** @var ItemInterface $newSubmenu */
        $newSubmenu = $menu
            ->getChild('configuration');

        $newSubmenu->addChild('mail-tester', [
                'route' => 'sylius_admin_mail_tester',
            ])
            ->setAttribute('type', 'link')
            ->setLabel('sylius.menu.admin.main.configuration.mail_tester')
            ->setLabelAttribute('icon', 'clock');
    }
}
