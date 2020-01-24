<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Resolver;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormTypeInterface;

final class FormTypeResolver
{
    /** @var \Doctrine\Common\Collections\ArrayCollection<int, ResolvableFormTypeInterface> */
    private $formTypes;

    public function getFormType(string $emailKey): FormTypeInterface
    {
        if (null === $this->formTypes) {
            $this->formTypes = new ArrayCollection();
        }

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
        if (null === $this->formTypes) {
            $this->formTypes = new ArrayCollection();
        }
        if (false === $this->formTypes->contains($resolvableForm)) {
            $this->formTypes->add($resolvableForm);
        }
    }
}
