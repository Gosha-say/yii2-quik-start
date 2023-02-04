How to start
-------------------

```php
        'db'     => [
            'class'    => Connection::class,
            'dsn'      => 'pgsql:host=localhost;dbname=fixprice',
            'username' => 'testSolution',
            'password' => 'EmptyPassword',
            'charset'  => 'utf8',
        ],
```


1. `git@github.com:Gosha-say/yii2-quik-start.git`
(branch fixprice-test-task)
2. `composer install`
3. `init`
4. `php yii migrate`
5. `php yii start-up`
6. `php yii migrate --migrationPath=@yii/rbac/migrations`
7. `php yii start-up/init-rbac`
8. `php yii serve --docroot="frontend/web"`
9. Go to [http://127.0.0.1:8080](http://127.0.0.1:8080)
