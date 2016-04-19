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

## Structure

Giixer defines its own easy to maintain and extend application structure which
is sligtly different than default gii generated structure. Below you can find 
what all and how giixer generates.

### ActiveRecords

Giixer uses it own ActiveRecords (AR) nested strucutre. Below are all 4 ARs 
with descriptions about what they stand for. Each is places on adequate levels as in applicaiton.

1. **Base AR**
    * top AR which is maintained by giixer itself only.
    * this AR always extends **GxActiveRecord**
    * manual changes to this AR may be lost after next giixer generation
    * file for this AR is placed in `common\models\db\base` or `common\modules\modulename\models\db\base`
2. **Common AR**
    * first editable AR which extends previous **Base AR**
    * changes to this AR **will not** be lost after following giixer generation
    * file for this AR is placed in `common\models\db` or `common\modules\modulename\models\db`
3. **Frontend/Backend AR**
    * low level AR which extends **Common AR**
    * these AR models lie in separate yii2 application `frontend` and `backend`
    * these models are the only ones which should be used directly by each aplication
    * these AR's namespaces can be `app\models\db` or `app\modules\modulename\models\db` 
    * files for these ARs are places in corresponding location as their namespaces but `app` part is replaces by `frontend` or `backend`

This AR model structure gives you opportunity to easily change your DB strucutre
and still be able to regenerate your AR models without loosing your current code changes

Because of same namespace for backend and frontend AR you can easily move some application logic to common scope
and avoid code duplication while AR models will be still found.

> Above structure is shown on [this diagram](https://drive.google.com/file/d/0B4fdy0PlE1nybUhLUFBiOTU0VnM/view?usp=sharing)

### ActiveQueries

Each AR model is generated with its custom ActiveQuery class which is asigned to
**Base AR**.

Giixer creates following 3 ActiveQuery (AQ) class during AR model generation.

1. **Common AQ**
    * this AQ always extends `\yii\db\ActiveQuery`
    * this AQ file is placed in `common\models\db\base` or `common\modules\modulename\models\db\base`
2. **Frontend/Backend AQ**
    * low level AQ which extends **Common AQ**
    * these AQ are always used by **Base AR**
    * these AQ's namespaces can be `app\models\db\query` or `app\modules\modulename\models\db\query` 
    * files for these AQs are places in corresponding location as their namespaces but `app` part is replaces by `frontend` or `backend`


Base AR will automatically loads appropriate AQ based on current application scope even 
both low level AQ has same namespace for backend and frontend. That is because frontend application 
does not have access to backend application scope and vice versa.

> Above structure is shown on [this diagram](https://drive.google.com/file/d/0B4fdy0PlE1nyM1ZjZmRMZWdhS2c/view?usp=sharing)

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
for some class does not match the default one. For instance if your application
is divided into modules and you need to generate classes for these modules.

```
[
    '^ModA[a-zA-Z]+Form$' => 'app\\modules\\moda\models\\forms'
    '^ModB[a-zA-Z]+Form$' => 'app\\modules\\modb\\models\\forms'
    '^ModA[a-zA-Z]+Search$' => 'app\\modules\\moda\\models\\db\\search',
    '^ModB[a-zA-Z]+Search$' => 'app\\modules\\modb\\models\\db\\search',
]
```

Regex is used as array keys and required namespace is used for array values. 
Giixer than use appropriate namespaces for matched class names and generates
its files in path corresponding namespace.

---

#### `controllerBackendBaseClass` option

Defines base class for backend controller. Must be valid namespace of existing class.

If is set the custom base class controller or its parents must extends **GxController**.
Otherwise the **GxController** will be used directly as parent class.

> **GxController** extends default `\yii\web\Controller`.

```
[
    'controllerBackendBaseClass' => 'backend\\controllers\\base\\BaseController'
]
```

For option above the backend generated controller will `extend backend\\controllers\\base\\BaseController`

---

#### `controllerFrontendBaseClass` option

Same purpose as **controllerBackendBaseClass** but for frontend controllers.

---

#### `helperRouteBaseClass` option

Defines base class for all route helpers generated by giixer.

If is set the custom base route helper class or its parents must extends **GxRouteHelper**.
Otherwise the **GxRouteHelper** will be used directly as parent class.

```
[
    'helperRouteBaseClass' => 'common\\components\\helpers\\url\\UrlRouteHelper'
]
```

---

#### `helperRuleBaseClass` option

Defines base class for all rule helpers generated by giixer.

If is set the custom base route helper class or its parents must extends **GxUrlRuleHelper**.
Otherwise the **GxUrlRuleHelper** will be used directly as parent class.

```
[
    'helperRuleBaseClass' => 'common\\components\\helpers\\url\\UrlRuleHelper'
]
```

---

#### `translations` option

Defines which translations files should be automatically generated. This options is 
defined by array containing languages codes.

```
['en', 'de', 'cs']
```

For option above english, german and czech translation files will be generated.

---