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
    private const NUMBER_OF_ITEMS = 25;

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $queryBuilderNormalizer = function (Options $options, $queryBuilder): QueryBuilder {
            if (\is_callable($queryBuilder)) {
                $queryBuilder = $queryBuilder($options['em']->getRepository($options['class']));
            } else {
                /** @var EntityRepository $repository */
                $repository = $options['em']->getRepository($options['class']);
                $queryBuilder = $repository->createQueryBuilder('o');
            }

            if (!$queryBuilder instanceof QueryBuilder) {
                throw new UnexpectedTypeException($queryBuilder, \Doctrine\ORM\QueryBuilder::class);
            }
            $queryBuilder->setMaxResults($options['limit'])
                ->orderBy('o.id', 'DESC')
            ;

            return $queryBuilder;
        };

        $resolver
            ->setNormalizer('query_builder', $queryBuilderNormalizer)
            ->setDefined('limit')
            ->setDefault('limit', self::NUMBER_OF_ITEMS)
            ->setAllowedTypes('limit', 'int')
        ;
    }
}
