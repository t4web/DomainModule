<?php

return [
    'service_manager' => [
        'abstract_factories' => [
            'T4webBase\Module\ConfigAbstractFactory',
            'T4webBase\Domain\Factory\EntityAbstractFactory',
            'T4webBase\Domain\Mapper\DbMapperAbstractFactory',
            'T4webBase\Domain\Repository\DbRepositoryAbstractFactory',
            'T4webBase\Db\TableGatewayAbstractFactory',
            'T4webBase\Db\TableAbstractFactory',
            'T4webBase\Domain\Criteria\CriteriaFactoryAbstractFactory',
        ],
    ],
];
