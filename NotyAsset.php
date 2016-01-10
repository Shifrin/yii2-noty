<?php
/**
 * NotyAsset Class File
 *
 * This is a helper class which is used to register required widget assets.
 *
 * @author Mohammad Shifreen
 * @link http://www.yiiframework.com/extension/yii2-noty/
 * @copyright 2016 Mohammed Shifreen
 * @license https://github.com/Shifrin/yii2-noty/blob/master/LICENSE.md
 */

namespace shifrin\noty;

use yii\web\AssetBundle;


class NotyAsset extends AssetBundle
{

    public $sourcePath = '@bower/noty';
    public $animateCss;
    public $buttonsCss;
    public $fontAwesomeCss;
    public $js = [
        'js/noty/packaged/jquery.noty.packaged.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * @inheritdoc
     * Register css files as per the request
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        if ($this->animateCss) {
            $this->css[] = 'demo/animate.css';
        }

        if ($this->fontAwesomeCss) {
            $this->css[] = 'demo/font-awesome/css/font-awesome.min.css';
        }

        if ($this->buttonsCss) {
            $this->css[] = 'demo/buttons.css';
        }

        parent::registerAssetFiles($view);
    }

}