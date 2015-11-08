<?php

return [
    'service_manager' => [
        'abstract_factories' => [
            'T4web\DomainModule\Service\CreatorAbstractFactory',
            'T4web\DomainModule\EntityFactoryAbstractFactory',

            'T4web\DomainModule\Infrastructure\RepositoryAbstractFactory',
            'T4web\DomainModule\Infrastructure\MapperAbstractFactory',
        ],
        'invokables' => [
            'T4webInfrastructure\CriteriaFactory' => 'T4webInfrastructure\CriteriaFactory',
        ],
    ],
];
