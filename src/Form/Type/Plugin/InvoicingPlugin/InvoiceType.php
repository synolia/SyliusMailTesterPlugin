<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type\Plugin\InvoicingPlugin;

use Sylius\InvoicingPlugin\Entity\Invoice;
use Sylius\InvoicingPlugin\Provider\InvoiceFileProviderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Synolia\SyliusMailTesterPlugin\Form\Type\AbstractType;

final class InvoiceType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'invoice_generated';

    public function __construct(
        private readonly InvoiceFileProviderInterface $invoiceFileProvider,
        #[Autowire(param: 'sylius_invoicing.pdf_generator.enabled')]
        private $hasEnabledPdfFileGenerator,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!class_exists(Invoice::class)) {
            return;
        }

        $builder->add('invoice', EntityType::class, [
            'class' => Invoice::class,
            'choice_label' => 'number',
        ]);
    }

    public function getAttachments(FormInterface $form): array
    {
        $invoice = $form->get('invoice')->getData();
        if (!$invoice instanceof Invoice) {
            return [];
        }

        if (!$this->hasEnabledPdfFileGenerator) {
            return [];
        }

        return [$this->invoiceFileProvider->provide($invoice)->fullPath()];
    }
}
