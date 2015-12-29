[![Build Status](https://travis-ci.org/remoblaser/lazy-artisan.svg?branch=master)](https://travis-ci.org/remoblaser/lazy-artisan)

#LazyArtisan
Lazy Artisan automatically adds and manages your Service Providers and Facades in your app config. If you import a composer package, you do not need to touch your app config, everything is done automatically! <br />
**Currently it only adds Facades and ServiceProviders, it does not remove them if you remove a Composer package!**


##NOTE!
This is a first draft, feel free to create pull requests. Might have a lot of bugs so be careful with the usage!
Will continue working on it, tests are yet to come.


##Example
If i would like to have a Laravel package in my Application, i usually need to add it to the composer.json, add the Service Providers in my app config and maybe even need to register my Facades.
The last two steps are no longer required with LazyArtisan. Everything will be added automatically after a composer update!

If you would like to manually manage Service Providers / Facades, this is possible too, since these are basic artisan commands called `php artisan generate:facades` and `php artisan generate:providers`


##Usage
### Install through composer
`composer require remoblaser/lazy-artisan`

### Add Service Provider
Add the Service Provider to your config/app.php (luckily this is the last time you need to do this!):
```php
'providers' => array(
		...
        Remoblaser\LazyArtisan\LazyArtisanServiceProvider::class,
	),
```

### Register post update command
In order to let composer execute the commands automatically, we need to update our composer.json and tell it, we would like to run the LazyArtisan commands after a composer update. Change the scripts part like so:
```
"scripts": {
		"post-install-cmd": [
			"php artisan clear-compiled",
			"php artisan optimize"
		],
		"post-update-cmd": [
			"php artisan clear-compiled",
			"php artisan optimize",
			"php artisan generate:providers",
			"php artisan generate:facades"
		],
		"post-create-project-cmd": [
			"php -r \"copy('.env.example', '.env');\"",
			"php artisan key:generate"
		]
	},
```



###Info
If you like my work, i would appreciate it if you would spread it! Thank you!
You can contact me through [Twitter](https://twitter.com/remoblaser)
