![Laravel Owner](http://www.letsbeinventive.co.uk/img/laravel-owner-sm.png)

# Laravel Owner

A simple package that allows Eloquent models to "own" each other, or "be owned" by another model. Supports many to many relationships.

Examples could include: 

1. a user owning a blog post
2. a user *and* a team owning multiple files
3. record being owned by many organisations

## Installation
Install using composer: 

```sh
composer require inventive/laravel-owner
```

Add the following to `config/app.php`:

```php
Inventive\LaravelOwner\OwnerServiceProvider::class,
```
Publish the migrations and config files:

```sh
php artisan vendor:publish
```
Run the migrations:

```sh
php artisan migrate
```

### Add necessary traits your Eloquent models:

If the model can be an owner:

```php
use Inventive\LaravelOwner\Traits\Owns;
	
class User extends Model
{
	use Owns;
}
```

If the model can be owned by another model:

```php
use Inventive\LaravelOwner\Traits\HasOwner;
	
class Resource extends Model
{
	use HasOwner;
}
```

## Usage
### "Owner" model:

Create an ownership:

```php
$user->own($model);
```
Remove an ownership:

```php
$user->disown($model);
```

Return a collection of *all* the models owned by the parent model:

```php
$user->owns();
```

Does the user own this model?

```php
$user->ownsModel($model);
```

Which models of this type does the parent model own?
This method either takes a child model, or a name-spaced class name.

```php
$user->ownsModelType($model); // Use a model
$user->ownsModelType(‘App\Resource’); // Use class name
```

### "Owned" model:
Return a collection of all the model's owners:

```php
$model->owners();
```
Is the model is owned by another model?

```php
$model->isOwnedBy($owner);
```
Add an owner to the model:

```php
$model->addOwner($owner);
```
Remove an owner from the model

```php
$model->removeOwner($owner);
```
## License

MIT


