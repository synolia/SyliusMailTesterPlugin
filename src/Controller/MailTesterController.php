<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Controller;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
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

    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(
        FormTypeResolver $formTypeResolver,
        FlashBagInterface $flashBag,
        TranslatorInterface $translator,
        ChannelContextInterface $channelContext
    ) {
        $this->formTypeResolver = $formTypeResolver;
        $this->flashBag = $flashBag;
        $this->translator = $translator;
        $this->channelContext = $channelContext;
    }

    public function mailTester(Request $request, SenderInterface $sender): Response
    {
        /** @var array $mailTester */
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
            $formData = $form->getData();

            if ($mailTester['subjects'] === ChoiceSubjectsType::EVERY_SUBJECTS) {
                /** @var AbstractType $formSubject */
                foreach ($this->formTypeResolver->getAllFormTypes() as $formSubject) {
                    $sender->send($formSubject->getCode(), [$formData['recipient']], $this->getMailData($form, $formSubject->getCode()));
                }
            }
            if ($mailTester['subjects'] !== ChoiceSubjectsType::EVERY_SUBJECTS) {
                $sender->send($formData['subjects'], [$formData['recipient']], $this->getMailData($form, 'form_subject_chosen'));
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

    private function getMailData(FormInterface $form, string $type): array
    {
        $emailData = [];

        if ($form->has($type)) {
            $emailData = $form->getData()[$type];
        }

        $emailData['localeCode'] = 'en_US';
        $emailData['channel'] = $this->channelContext->getChannel();

        return $emailData;
    }
}
