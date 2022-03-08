
```
composer require annotations
```

<p>
 create first controller
</p>



```
composer require symfony/maker-bundle --dev

symfony console make:controller MovieController
```

<p>go to route </p>


```

http://127.0.0.1:8000/movie

```

<p>Route parameters</p>

```

symfony console debug:router

```

<p>add twig for project</p>

```
composer require twig

```


<p>Create a database using doctrine</p>


```

symfony console

composer require orm

symfony console list doctrine

```

<p>database connection </p>

```

composer require symfony/orm-pack
composer require --dev symfony/maker-bundle
symfony console doctrine:database:create

```