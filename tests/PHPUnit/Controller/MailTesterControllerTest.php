<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\PHPUnit\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MailTesterControllerTest extends WebTestCase
{
    private const URL = '/admin/mail/tester';

    private KernelBrowser $client;

    private ?AdminUserInterface $adminUser = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->client->loginUser($this->createAdminUser(), 'admin');
    }

    protected function tearDown(): void
    {
        if (null !== $this->adminUser) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = self::getContainer()->get(EntityManagerInterface::class);
            $adminUser = $entityManager->find($this->adminUser::class, $this->adminUser->getId());
            if (null !== $adminUser) {
                $entityManager->remove($adminUser);
                $entityManager->flush();
            }
            $this->adminUser = null;
        }

        parent::tearDown();
    }

    public function testItDisplaysTheMailTesterPage(): void
    {
        $this->client->request('GET', self::URL);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form[name="mail_tester"]');
    }

    public function testItSendsTheChosenEmailWhenTheFormIsSubmitted(): void
    {
        $crawler = $this->client->request('GET', self::URL);

        $form = $crawler->filter('#mail_tester_change_form_subject')->form([
            'mail_tester[recipient]' => 'recipient@example.com',
            'mail_tester[subjects]' => Emails::CONTACT_REQUEST,
        ]);
        $crawler = $this->client->submit($form);

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('#mail_tester_form_subject_chosen_data_email');

        $form = $crawler->filter('#mail_tester_submit')->form([
            'mail_tester[form_subject_chosen][data][email]' => 'customer@example.com',
            'mail_tester[form_subject_chosen][data][message]' => 'Hello from the mail tester',
        ]);
        $this->client->submit($form);

        self::assertResponseIsSuccessful();
        self::assertEmailCount(1);
        self::assertSelectorExists('.alert-success');
    }

    private function createAdminUser(): AdminUserInterface
    {
        $container = self::getContainer();

        /** @var FactoryInterface<AdminUserInterface> $factory */
        $factory = $container->get('sylius.factory.admin_user');
        $adminUser = $factory->createNew();
        $adminUser->setUsername('mail_tester_' . bin2hex(random_bytes(4)));
        $adminUser->setEmail($adminUser->getUsername() . '@example.com');
        $adminUser->setPlainPassword('mail_tester');
        $adminUser->setLocaleCode('en_US');
        $adminUser->setEnabled(true);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);
        $entityManager->persist($adminUser);
        $entityManager->flush();

        return $this->adminUser = $adminUser;
    }
}
