yii2-giixer
===========

Extended gii module for Yii2 including a bunch of useful handler, helpers, traits
and other components. This module generates required models, controllers and other
classes with dependency on own components. Default yii-gii generator is not available
and is replaced by when your are usint this module.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require dlds/yii2-giixer
```

or add

```
"dlds/yii2-giixer": "~3.0.0"
```

to the `require` section of your `composer.json` file.

## Migration

There is not any migration required to run. Module itself does not store data in DB.

## Configuration

Enables gii module in your config file by adding it to app bootstrap.

```
$config['bootstrap'][] = 'gii';
```

Replace default gii module class with giixer one

```
$config['modules']['gii'] = [
    'class' => 'dlds\giixer\Module',
];
```

You can also modify giixer module behavior to your requirements by 
setting additional config options.

#### `namespaces` option

Defines namespaces map to generated classes. This is useful if the namespace
for some class does not match default one. For instance if your application
is divided to modules and you need to generate classes for these modules.

```
[
    '^ModA[a-zA-Z]+Form$' => 'app\\modules\\moda\models\\forms'
    '^ModB[a-zA-Z]+Form$' => 'app\\modules\\modb\\models\\forms'
]
```

Regex is used as array keys and required namespace is used for array values. 
Giixer than use appropriate namespaces for matched class names and generates
its files in path corresponding namespace.


---

