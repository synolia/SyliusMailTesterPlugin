<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Controller;

use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Synolia\SyliusMailTesterPlugin\Form\Type\MailTesterType;
use Synolia\SyliusMailTesterPlugin\Resolver\FormTypeResolver;

final class MailTesterController extends AbstractController
{
    /** @var FormTypeResolver */
    private $formTypeResolver;

    /** @var FlashBagInterface */
    private $flashBag;

    public function __construct(FormTypeResolver $formTypeResolver, FlashBagInterface $flashBag)
    {
        $this->formTypeResolver = $formTypeResolver;
        $this->flashBag = $flashBag;
    }

    public function mailTester(Request $request, SenderInterface $sender): Response
    {
        $mailTester = $request->get('mail_tester');

        $form = $this->createForm(MailTesterType::class);
        if ($mailTester['subjects'] !== null) {
            $form = $this->createForm(MailTesterType::class, [
                'form_subject' => $this->formTypeResolver->getFormType($mailTester['subjects']),
                'subject' => $mailTester['subjects'],
                'recipient' => $mailTester['recipient'],
            ]);
        }

        $form->handleRequest($request);

        if (isset($mailTester['change_form_subject']) && $mailTester['change_form_subject'] === '') {
            $form = $this->createForm(MailTesterType::class, [
                'form_subject' => $this->formTypeResolver->getFormType($mailTester['subjects']),
                'subject' => $mailTester['subjects'],
                'recipient' => $mailTester['recipient'],
            ]);

            return $this->render('@SynoliaSyliusMailTesterPlugin/Admin/MailTester/mail_tester.html.twig', ['form' => $form->createView()]);
        }

        if (isset($mailTester['submit']) && $form->isValid()) {
            try {
                $sender->send($form->getData()['subjects'], [$form->getData()['recipient']], $form->getData()['form_subject_chosen']);
                $this->flashBag->add('success', 'sylius.ui.admin.mail_tester.success');
            } catch (\Swift_RfcComplianceException $exception) {
                $this->flashBag->add('error', $exception->getMessage());
            }
        }

        return $this->render('@SynoliaSyliusMailTesterPlugin/Admin/MailTester/mail_tester.html.twig', ['form' => $form->createView()]);
    }
}
