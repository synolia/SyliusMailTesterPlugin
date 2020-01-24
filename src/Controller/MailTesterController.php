<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Controller;

use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Synolia\SyliusMailTesterPlugin\Form\Type\AbstractType;
use Synolia\SyliusMailTesterPlugin\Form\Type\ChoiceSubjectsType;
use Synolia\SyliusMailTesterPlugin\Form\Type\MailTesterType;
use Synolia\SyliusMailTesterPlugin\Resolver\FormTypeResolver;

final class MailTesterController extends AbstractController
{
    /** @var FormTypeResolver */
    private $formTypeResolver;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(FormTypeResolver $formTypeResolver, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->formTypeResolver = $formTypeResolver;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function mailTester(Request $request, SenderInterface $sender): Response
    {
        $mailTester = $request->get('mail_tester');

        $form = $this->createForm(MailTesterType::class);
        if ($mailTester['subjects'] !== null && $mailTester['subjects'] !== ChoiceSubjectsType::EVERY_SUBJECTS) {
            $form = $this->createForm(MailTesterType::class, [
                'form_subject' => $this->formTypeResolver->getFormType($mailTester['subjects']),
                'subject' => $mailTester['subjects'],
                'recipient' => $mailTester['recipient'],
            ]);
        }

        if ($mailTester['subjects'] === ChoiceSubjectsType::EVERY_SUBJECTS) {
            $form = $this->getEveryForms($mailTester['recipient']);
        }

        $form->handleRequest($request);

        if (isset($mailTester['change_form_subject']) && $mailTester['change_form_subject'] === '' && $mailTester['subjects'] !== ChoiceSubjectsType::EVERY_SUBJECTS) {
            $form = $this->createForm(MailTesterType::class, [
                'form_subject' => $this->formTypeResolver->getFormType($mailTester['subjects']),
                'subject' => $mailTester['subjects'],
                'recipient' => $mailTester['recipient'],
            ]);

            return $this->render('@SynoliaSyliusMailTesterPlugin/Admin/MailTester/mail_tester.html.twig', ['form' => $form->createView()]);
        }

        if (isset($mailTester['submit']) && $form->isValid()) {
            $this->sendMail($mailTester, $sender, $form);
        }

        return $this->render('@SynoliaSyliusMailTesterPlugin/Admin/MailTester/mail_tester.html.twig', ['form' => $form->createView()]);
    }

    private function sendMail(array $mailTester, SenderInterface $sender, FormInterface $form): void
    {
        try {
            $this->flashBag->add('success', $this->translator->trans('sylius.ui.admin.mail_tester.success'));
            if ($mailTester['subjects'] === ChoiceSubjectsType::EVERY_SUBJECTS) {
                /** @var AbstractType $formSubject */
                foreach ($this->formTypeResolver->getAllFormTypes() as $formSubject) {
                    $sender->send($formSubject->getCode(), [$form->getData()['recipient']], $form->getData()[$formSubject->getCode()]);
                }
            }
            if ($mailTester['subjects'] !== ChoiceSubjectsType::EVERY_SUBJECTS) {
                $sender->send($form->getData()['subjects'], [$form->getData()['recipient']], $form->getData()['form_subject_chosen']);
            }
        } catch (\Swift_RfcComplianceException $exception) {
            $this->flashBag->add('error', $exception->getMessage());
        }
    }

    private function getEveryForms(string $recipient): FormInterface
    {
        $form = $this->createForm(MailTesterType::class, [
            'form_every_subjects' => $this->formTypeResolver->getAllFormTypes(),
            'subject' => ChoiceSubjectsType::EVERY_SUBJECTS,
            'recipient' => $recipient,
        ]);

        return $form;
    }
}
