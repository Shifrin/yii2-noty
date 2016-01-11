# yii2-noty
Yii2 widget for Noty jQuery notification plugin, [Visit Noty](http://ned.im/noty/#/about)

> For Yii1: Please check [yii-noty](https://github.com/Shifrin/yii-noty)

## Installation:
The preferred way to install this extension is through [composer](http://getcomposer.org/download/)
> Note: The instructions below refer to the global composer installation. You might need to replace `composer` with php `composer.phar` (or similar) for your setup.

Direct install:
```
composer require shifrin/yii2-noty 1.0.1
```
or add it manually to the `composer.json` and update `composer`
```
"shifrin/yii2-noty": "1.0.1",
```
```
composer update
```

## Usage Details:
Add the widget in your main layout file like below,
```php
<?php \shifrin\noty\NotyWidget::widget([
    'options' => [ // you can add js options here, see noty plugin page for available options
        'dismissQueue' => true,
        'layout' => 'topCenter',
        'theme' => 'relax',
        'animation' => [
            'open' => 'animated flipInX',
            'close' => 'animated flipOutX',
        ],
        'timeout' => false,
    ],
    'enableSessionFlash' => true,
    'enableIcon' => true,
    'registerAnimateCss' => true,
    'registerButtonsCss' => true,
    'registerFontAwesomeCss' => true,
]); ?>
```

This widget will create a JS function `Noty()`, so it will be available globally and you can call this function in your custom JS codes. See the following example below
```javascript
var n = Noty('id');
$.noty.setText(n.options.id, 'Welcome to our site!');
$.noty.setType(n.options.id, 'information');
```

You can set the flash messages like this in your controller or anywhere you need. However, if you set `enableSessionFlash` to `false` it won't be affected.
```php
\Yii::$app->session->setFlash('type', 'Message here');
```

Replace `type` with available types
  
### Available Types:
  * success
  * error
  * warning
  * information
  * alert
