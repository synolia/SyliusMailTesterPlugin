<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Mail Tester Plugin</h1>
<p align="center">
    <a href="https://packagist.org/packages/synolia/sylius-mail-tester-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/synolia/sylius-mail-tester-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/synolia/sylius-mail-tester-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/synolia/sylius-mail-tester-plugin.svg" />
    </a>
    <a href="https://travis-ci.com/synolia/SyliusMailTesterPlugin" title="Build status" target="_blank">
        <img src="https://api.travis-ci.com/synolia/SyliusMailTesterPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/synolia/sylius-mail-tester-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/synolia/sylius-mail-tester-plugin/downloads" />
    </a>
</p>
<p align="center">Test how your emails are rendered by sending them to your email from your Sylius admin panel.</p>

![Capture](/etc/capture.png "Capture")
![SentEmail](/etc/capture-email.png "Sent Email")

## Features

* See a list of all sylius emails
* Send example email one by one to your email address
* Send all example emails to your email address
* For each email, you have to select the entity to be used in the template.

## Installation

1. Add the bundle and dependencies in your composer.json :
    ```shell script
    $ composer require synolia/sylius-mail-tester-plugin
    ```
2. Enable the plugin in your `config/bundles.php` file by add
    ```php
    Synolia\SyliusMailTesterPlugin\SynoliaSyliusMailTesterPlugin::class => ['all' => true],
    ```

3. Import routing in your `config/routes.yaml` file:

    ```yaml
    synolia_mail_tester:
        resource: "@SynoliaSyliusMailTesterPlugin/Resources/config/admin_routing.yaml"
        prefix: /admin
    ```
4. Copy plugin migrations to your migrations directory (e.g. `src/Migrations`) and apply them to your database:

    ```shell script
    cp -R vendor/synolia/sylius-mail-tester-plugin/src/Migrations/* src/Migrations
    bin/console doctrine:migrations:migrate
    ```

## Usage

* Log into admin panel
* Click on `Mail tester` in the Configuration section in main menu
* Enter the email address that will receive the example email template
* Select the email you would like to be sent.
* Click on `Choose subject`
* Fill empty boxes and select your entities.
* Click the Submit button.

## Allow sending your custom emails
In order to be able to send a custom email with variables, you have to add a form type that will add those variables to the form.
The important part is that your Form Type must implement ResolvableFormTypeInterface in order to be discovered by our FormTypeResolver.
```php
<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Component\Core\Model\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

public class CustomEmailType extends AbstractType implements ResolvableFormTypeInterface
{
    /** @var string */
    private const SYLIUS_EMAIL_KEY = 'custom_email'; //this should match your email identification key in sylius_mailer.yaml.

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        /**
         * The key 'order' represent the name of the variable in your template.
         * Then you specify the type of the variable.
         * In this example we provide a list of all available orders.
         */
        $builder->add('order', EntityType::class, [
            'class' => Order::class,
            'choice_label' => 'number',
        ]);
    }

    public function support(string $emailKey): bool
    {
        return $emailKey === self::SYLIUS_EMAIL_KEY;
    }

    public function getCode(): string
    {
        return self::SYLIUS_EMAIL_KEY;
    }

    public function getFormType(string $emailKey): ResolvableFormTypeInterface
    {
        return $this;
    }
}
```

## Development

See [How to contribute](CONTRIBUTING.md).

## License

This library is under the MIT license.

## Credits

Developed by [Synolia](https://synolia.com/).
