<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LimitedEntityType extends EntityType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $queryBuilderNormalizer = function (Options $options, $queryBuilder) {
            if (\is_callable($queryBuilder)) {
                $queryBuilder = $queryBuilder($options['em']->getRepository($options['class']));

                if (null !== $queryBuilder && !$queryBuilder instanceof QueryBuilder) {
                    throw new UnexpectedTypeException($queryBuilder, 'Doctrine\ORM\QueryBuilder');
                }
            } else {
                /** @var EntityRepository $repository */
                $repository = $options['em']->getRepository($options['class']);
                $queryBuilder = $repository->createQueryBuilder('o');
            }

            $queryBuilder->setMaxResults($options['limit']);

            return $queryBuilder;
        };

        $resolver
            ->setNormalizer('query_builder', $queryBuilderNormalizer)
            ->setDefined('limit')
            ->setDefault('limit', 20)
            ->setAllowedTypes('limit', 'int')
        ;
    }
}
