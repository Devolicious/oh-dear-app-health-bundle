# OhDear App Health bundle
A simple Symfony bundle to integrate OhDear Application Health monitoring.

## Installation
### Step 1: Download the Bundle
```bash
composer require devolicious/oh-dear-app-health-bundle
```

if you are using Symfony Flex, the bundle will be automatically enabled. Otherwise, enable it by adding it to your `bundles.php` file.

```php
// bundles.php

return [
    // ...
    Devolicious\OhDearAppHealthBundle\OhDearAppHealthBundle::class => ['all' => true],
];
```

### Step 2: Configure the routes

add this to your `config/routes.yaml` file:

```yaml
_health_check:
    resource: '@OhDearAppHealthBundle/Resources/config/routing.yaml'
```

or if you want to customize the route, just add your own route yaml file in `config/routes/` containing the following:

```yaml
my_custom_health_check:
    path: /my-custom-health-check-route
    controller: Devolicious\OhDearAppHealthBundle\Controller\HealthController
    methods: [GET, HEAD]
```

## How to use it

Create your own checkers by implementing the `Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface` and tag them with `oh_dear_app_health.checker`:

```yaml
# config/services.yaml
services:

    _instanceof:
        ...
        Devolicious\OhDearAppHealthBundle\Checker\CheckerInterface:
          tags: [ 'oh_dear_app_health.checker' ]
```

That's it! Your checkers will be automatically executed when the health check route is called.

But, you're also able to cache the result of your checkers. When you run the command `health:check` all your checkers 
will run and by default it will be stored in the default cache pool. You can change this overriding the `ResultStore` interface with any service implementing it.

```yaml
services:
    ...
    # default configuration
    Devolicious\OhDearAppHealthBundle\Store\RequestStore: '@Devolicious\OhDearAppHealthBundle\Store\CachePoolStore'

    # custom configuration
    Devolicious\OhDearAppHealthBundle\Store\RequestStore: '@App\Store\MyCustomStore'
```

Within each checker you can define the frequency of how often the check should be executed.
