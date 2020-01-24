<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Controller;

use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Synolia\SyliusMailTesterPlugin\Form\Type\MailTesterType;

final class MailTesterController extends AbstractController
{
    public function mailTester(Request $request, SenderInterface $sender): Response
    {
        $form = $this->createForm(MailTesterType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sender->send('contact_request', [$form->getData()['recipient']], ['data' => ['email' => 'test@test.com', 'message' => 'test']]);
        }

        return $this->render('@SynoliaSyliusMailTesterPlugin/Admin/MailTester/mail_tester.html.twig', ['form' => $form->createView()]);
    }
}
