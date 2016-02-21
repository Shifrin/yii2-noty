<?php
/**
 * NotyWidget Class File
 *
 * It's a yii2 widget for alert type of messages that can be shown to the end user
 * This widget build with noty jQuery plugin v2.3.7. @link http://ned.im/noty/
 *
 * @author Mohammad Shifreen
 * @link http://www.yiiframework.com/extension/yii2-noty/
 * @copyright 2016 Mohammed Shifreen
 * @license https://github.com/Shifrin/yii2-noty/blob/master/LICENSE.md
 */

namespace shifrin\noty;

use Yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;


class NotyWidget extends Widget
{

    /**
     * Noty plugin JS Options
     * @var array
     */
    public $options = [];
    /**
     * Enable Session Flash
     * @var bool defaults true
     */
    public $enableSessionFlash = true;
    /**
     * Enable Icon
     * @var bool defaults true
     */
    public $enableIcon = true;
    /**
     * Register animate.css
     * If animate.css already registered in your assets you can set it to false
     * @var bool defaults true
     */
    public $registerAnimateCss = true;
    /**
     * Register buttons.css
     * If bootstrap.css or any related css already registered in your assets you can set it to false,
     * otherwise this will override your buttons' styles
     * @var bool defaults true
     */
    public $registerButtonsCss = true;
    /**
     * Register font-awesome.css
     * If font-awesome.css already registered in your assets you can set it to false
     * @var bool defaults true
     */
    public $registerFontAwesomeCss = true;
    /**
     * Alert types
     * @var array
     */
    protected $types = [
        'error' => 'error',
        'success' => 'success',
        'information' => 'information',
        'warning' => 'warning',
        'alert' => 'alert'
    ];
    /**
     * Icons based on type
     * @var array
     */
    protected $icons = [
        'error' => 'fa fa-times-circle',
        'success' => 'fa fa-check-circle',
        'information' => 'fa fa-info-circle',
        'warning' => 'fa fa-exclamation-circle',
        'alert' => 'fa fa-bell-o',
        'notification' => 'fa fa-bell-o',
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();
        $this->registerPlugin();

        if ($this->enableSessionFlash) {
            $flashes = Yii::$app->session->getAllFlashes();
            
            if (empty($flashes)) {
                return;
            }
            
            $view = $this->getView();
            $script = "";

            foreach($flashes as $type => $message) {
                if (empty($message)) {
                    continue;
                }

                $type = $this->verifyType($type);
                $icon = $this->getIcon($type);
                $text = is_array($message) ? $icon . implode(' ', $message) : $icon . $message;
                $script .= "var {$type} = Noty('{$this->getId()}');\r\n";
                $script .= "$.noty.setText({$type}.options.id, '{$text}');\r\n";
                $script .= "$.noty.setType({$type}.options.id, '{$type}');\r\n";
            }
            
            $view->registerJs($script);
        }
    }

    /**
     * Register Noty plugin by creating a wrapper function called 'Noty()'
     * This will be available globally for use
     *
     * ~~~
     * js: var n = Noty('id');
     * $.noty.setText(n.options.id, 'Hi I am noty alert!');
     * $.noty.setType(n.options.id, 'information');
     * ~~~
     */
    public function registerPlugin()
    {
        $view = $this->getView();
        $options = !empty($this->options) ? Json::encode($this->options) : '';
        $js = <<< JS
            function Noty(widgetId, options) {
                var finalOptions = $.extend({}, $options, options);
                return noty(finalOptions);
            }
JS;

        $view->registerJs($js, View::POS_END);
    }

    /**
     * Register required assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        $asset = NotyAsset::register($view);
        $asset->animateCss = $this->registerAnimateCss;
        $asset->buttonsCss = $this->registerButtonsCss;
        $asset->fontAwesomeCss = $this->enableIcon && $this->registerFontAwesomeCss;
    }

    /**
     * Verify type.
     * If verify unsuccessful it will return default type called 'notification'
     * @param $type string
     * @return string
     */
    protected function verifyType($type) {
        if (array_key_exists($type, $this->types)) {
            return $this->types[$type];
        }

        // Return default
        return 'notification';
    }

    /**
     * Get icon according to the type
     * @param $type string
     * @return string
     */
    protected function getIcon($type) {
        if (!$this->enableIcon) {
            return '';
        }

        $class = $this->icons[$type];

        return Html::tag('i', '', ['class' => $class]) . ' ';
    }

}
