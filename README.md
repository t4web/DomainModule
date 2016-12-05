# Domain module

Master:
[![Build Status](https://travis-ci.org/t4web/DomainModule.svg?branch=master)](https://travis-ci.org/t4web/DomainModule)
[![codecov.io](http://codecov.io/github/t4web/DomainModule/coverage.svg?branch=master)](http://codecov.io/github/t4web/DomainModule?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/t4web/DomainModule/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/t4web/DomainModule/?branch=master)

ZF2 Module for [Domain implementation](https://github.com/t4web/Domain) and [Infrastructure implementation](https://github.com/t4web/Infrastructure). 
Provide dynamically setup Domain layer.

### Main Setup

#### By cloning project

1. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

```json
"require": {
    "t4web/domain-module": "~1.3.0"
}
```

2. Now tell composer to download DomainModule by running the command:

```bash
$ php composer.phar update
```

#### Post installation

1. Enabling it in your `application.config.php`file.

```php
<?php
return array(
    'modules' => array(
        // ...
        'T4web\DomainModule',
    ),
    // ...
);
```

### Quick start

Describe entity:
```php
class Task extends \T4webDomain\Entity {
    protected $name;
    protected $assigneeId;
    protected $status;
    protected $type;

    /**
     * @var Users\User\User
     */
    protected $assignee;

    public function __construct(array $data, Users\User\User $assignee = null) {
        parent::__construct($data);
        $this->assignee = $assignee;
    }

    /**
     * @return Users\User\User
     */
    public function getAssignee() {
        return $this->assignee;
    }
}
```

Describe entity_map config in your `module.config.php`:
```php
return [
    // ...
    
    'entity_map' => [
        'Task' => [
            // table name
            'table' => 'tasks',

            // optional, only if you have use short service names
            'entityClass' => 'Tasks\Task\Task',
            
            // optional, only if you have use short service names
            'collectionClass' => 'Tasks\Task\TaskCollection',

            // optional, by default 'id'
            'primaryKey' => 'id',

            // map entity field with table field
            'columnsAsAttributesMap' => [
                'id' => 'id',
                'name' => 'name',
                'assigneeId' => 'assignee_id',
                'status' => 'status',
                'type' => 'type',
            ],

            // optional, aliases for criteria - for pretty query args
            'criteriaMap' => [
                'id' => 'id_equalTo'
            ],

            // optional, relations for filtering and fetching aggregate entity
            'relations' => [
                'User' => ['tasks.assignee_id', 'users.id']
            ],
        ],
    ],
];
```

You can get Domain layer from ServiceManager:
```php
// in your controller
$creator = $serviceLocator->get('Task\Service\Creator');

$task = $creator->create(['name' => 'buy milk', 'type' => 2]);

if (!$task) {
    return ['errors' => $creator->getMessages()];
}

$repository = $serviceLocator->get('Task\Infrastructure\Repository');
/** @var Tasks\Task\Task $task */
$task = $repository->findById(123);

$repository = $serviceLocator->get('Task\Infrastructure\AggregateRepository');
$task = $repository->findWith('User')->findById(123);
/** @var Users\User\User $assignee */
$assignee = $task->getAssignee();
```

### Components
- `MODULE-NAME\ENTITY-NAME\Infrastructure\Repository`
- `MODULE-NAME\ENTITY-NAME\Service\Creator`
- `MODULE-NAME\ENTITY-NAME\Service\Deleter`
- `MODULE-NAME\ENTITY-NAME\Service\Updater`
- `MODULE-NAME\ENTITY-NAME\EntityFactory`

Service classes:
- `MODULE-NAME\ENTITY-NAME\Infrastructure\Config`
- `MODULE-NAME\ENTITY-NAME\Infrastructure\Mapper`
- `MODULE-NAME\ENTITY-NAME\Infrastructure\QueryBuilder`

We recommend use short service names - without module name

- `ENTITY-NAME\Infrastructure\Repository`
- `ENTITY-NAME\Service\Creator`
- `ENTITY-NAME\Service\Deleter`
- `ENTITY-NAME\Service\Updater`
- `ENTITY-NAME\EntityFactory`

Service classes:
- `ENTITY-NAME\Infrastructure\Config`
- `ENTITY-NAME\Infrastructure\Mapper`
- `ENTITY-NAME\Infrastructure\QueryBuilder`

When you use short service names - `entityClass` config parameter is required.
