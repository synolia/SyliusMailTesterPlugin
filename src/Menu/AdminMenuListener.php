<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main', method: 'addAdminMenuItems')]
final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        /** @var ItemInterface $newSubmenu */
        $newSubmenu = $menu->getChild('configuration');

        $newSubmenu->addChild('mail-tester', [
                'route' => 'sylius_admin_mail_tester',
            ])
            ->setAttribute('type', 'link')
            ->setLabel('sylius.menu.admin.main.configuration.mail_tester')
            ->setLabelAttribute('icon', 'mail')
        ;
    }
}
