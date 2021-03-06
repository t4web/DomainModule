<?php

return [
    'service_manager' => [
        'abstract_factories' => [
            'T4web\DomainModule\Service\CreatorAbstractFactory',
            'T4web\DomainModule\Service\DeleterAbstractFactory',
            'T4web\DomainModule\Service\UpdaterAbstractFactory',
            'T4web\DomainModule\EntityFactoryAbstractFactory',
            'T4web\DomainModule\EntityEventManagerAbstractFactory',

            'T4web\DomainModule\Infrastructure\RepositoryAbstractFactory',
            'T4web\DomainModule\Infrastructure\InMemoryRepositoryAbstractFactory',
            'T4web\DomainModule\Infrastructure\FinderAggregateRepositoryFactory',
            'T4web\DomainModule\Infrastructure\MapperAbstractFactory',
            'T4web\DomainModule\Infrastructure\CriteriaFactoryAbstractFactory',
            'T4web\DomainModule\Infrastructure\ConfigAbstractFactory',
            'T4web\DomainModule\Infrastructure\ConfigAbstractFactory',
        ],
    ],
];
