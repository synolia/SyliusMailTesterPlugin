<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Resolver;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final readonly class FormTypeResolver
{
    public function __construct(
        #[AutowireIterator(ResolvableFormTypeInterface::class)]
        private iterable $formTypes,
    ) {
    }

    public function getFormType(string $emailKey): ResolvableFormTypeInterface
    {
        /** @var ResolvableFormTypeInterface $formType */
        foreach ($this->formTypes as $formType) {
            if ($formType->support($emailKey)) {
                return $formType->getFormType($emailKey);
            }
        }

        throw new NoResolvableFormTypeFoundException(sprintf('No resolvable form found for email %s.', $emailKey));
    }

    public function getAllFormTypes(): iterable
    {
        return $this->formTypes;
    }
}
