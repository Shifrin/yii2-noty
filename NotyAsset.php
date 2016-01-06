<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 01/05/2016
 * Time: 3:48 PM
 */

namespace shifrin\noty;

use yii\web\AssetBundle;


class NotyAsset extends AssetBundle
{

    public $sourcePath = '@bower/noty';
    public $animateCss;
    public $fontAwesomeCss;
    public $css = [
        'demo/button.css'
    ];
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

        parent::registerAssetFiles($view);
    }

}