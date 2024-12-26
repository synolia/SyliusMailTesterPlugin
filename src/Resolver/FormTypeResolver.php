<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Resolver;

use Doctrine\Common\Collections\ArrayCollection;

final readonly class FormTypeResolver
{
    /** @var ArrayCollection<int, ResolvableFormTypeInterface> */
    private ArrayCollection $formTypes;

    public function __construct()
    {
        $this->formTypes = new ArrayCollection();
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

    public function addFormType(ResolvableFormTypeInterface $resolvableForm): void
    {
        if (false === $this->formTypes->contains($resolvableForm)) {
            $this->formTypes->add($resolvableForm);
        }
    }
}
