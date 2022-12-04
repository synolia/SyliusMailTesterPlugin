<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Controller;

use Sylius\Component\Core\Model\PromotionCoupon;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Synolia\SyliusMailTesterPlugin\Form\Type\AbstractType;
use Synolia\SyliusMailTesterPlugin\Form\Type\ChoiceSubjectsType;
use Synolia\SyliusMailTesterPlugin\Form\Type\MailTesterType;
use Synolia\SyliusMailTesterPlugin\Resolver\FormTypeResolver;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableMultipleFormTypeInterface;

final class MailTesterController extends AbstractController
{
    /** @var FormTypeResolver */
    private $formTypeResolver;

    /** @var TranslatorInterface */
    private $translator;

    /** @var array */
    private $emails;

    public function __construct(
        FormTypeResolver $formTypeResolver,
        TranslatorInterface $translator,
        array $emails
    ) {
        $this->formTypeResolver = $formTypeResolver;
        $this->translator = $translator;
        $this->emails = $emails;
    }

    public function mailTester(Request $request, SenderInterface $sender): Response
    {
        /** @var array $mailTester */
        $mailTester = $request->get('mail_tester', ['subjects' => null]);

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
            $this->sendMail($request, $mailTester, $sender, $form);
        }

        return $this->render('@SynoliaSyliusMailTesterPlugin/Admin/MailTester/mail_tester.html.twig', ['form' => $form->createView()]);
    }

    private function sendMail(Request $request, array $mailTester, SenderInterface $sender, FormInterface $form): void
    {
        try {
            $formData = $form->getData();

            if ($mailTester['subjects'] === ChoiceSubjectsType::EVERY_SUBJECTS) {
                /** @var AbstractType $formSubject */
                foreach ($this->formTypeResolver->getAllFormTypes() as $formSubject) {
                    if ($formSubject instanceof ResolvableMultipleFormTypeInterface) {
                        foreach ($formSubject->getCodes() as $code) {
                            if (array_key_exists($code, $this->emails)) {
                                $sender->send($code, [$formData['recipient']], $this->getMailData($form, $code));
                            }
                        }

                        continue;
                    }

                    if (array_key_exists($formSubject->getCode(), $this->emails)) {
                        $sender->send($formSubject->getCode(), [$formData['recipient']], $this->getMailData($form, $formSubject->getCode()));
                    }
                }
            }
            if ($mailTester['subjects'] !== ChoiceSubjectsType::EVERY_SUBJECTS) {
                $sender->send($formData['subjects'], [$formData['recipient']], $this->getMailData($form, 'form_subject_chosen'));
            }

            $request->getSession()->getFlashBag()->add('success', $this->translator->trans('sylius.ui.admin.mail_tester.success'));
        } catch (\Exception $exception) {
            $request->getSession()->getFlashBag()->add('error', $exception->getMessage());
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

        if (class_exists('Sylius\Plus\SyliusPlusPlugin')) {
            if ($form->has('form_subject_chosen') && $form->get('form_subject_chosen')->has('promotionCoupon')) {
                /** @var PromotionCoupon $promotionCoupon */
                $promotionCoupon = $form->get('form_subject_chosen')->get('promotionCoupon')->getData();
                $emailData['couponCode'] = $promotionCoupon->getCode();
            }

            if ($form->has('subjects') && $form->get('subjects')->getData() === ChoiceSubjectsType::EVERY_SUBJECTS) {
                /** @var PromotionCoupon $promotionCoupon */
                $promotionCoupon = $form->get('sylius_plus_loyalty_purchase_coupon')->getData()['promotionCoupon'];
                $emailData['couponCode'] = $promotionCoupon->getCode();
            }
        }

        $emailData['localeCode'] = $form->get('localeCode')->getData()->getCode();
        $emailData['channel'] = $form->get('channel')->getData();

        return $emailData;
    }
}
