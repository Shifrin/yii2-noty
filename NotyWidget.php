<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 01/05/2016
 * Time: 3:55 PM
 */

namespace shifrin\noty;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;


class NotyWidget extends Widget
{

    /**
     * Widget ID
     * @var string defaults noty
     */
    public $id = 'noty';
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
     * Register font-awesome.css
     * If font-awesome.css already registered in your assets you can set it to false
     * @var bool defaults true
     */
    public $registerFontAwesomeCss = true;

    protected $types = [
        'error' => 'error',
        'success' => 'success',
        'information' => 'information',
        'warning' => 'warning',
        'alert' => 'alert'
    ];
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

        $view = $this->getView();
        $options = !empty($this->options) ? Json::encode($this->options) : '';
        $script = "var Noty = generateNoty({$options})";

        if ($this->enableSessionFlash) {
            $flashes = Yii::$app->session->getAllFlashes();

            foreach($flashes as $type => $message) {
                if (empty($message)) {
                    continue;
                }

                $type = $this->verifyType($type);
                $icon = $this->getIcon($type);
                $text = $icon . is_array($message) ? implode(' ', $message) : $message;
                $script .= "var {$type} = generateNoty({$options})";
                $script .= "$.noty.setText({$type}.options.id, '{$text}')";
                $script .= "$.noty.setType({$type}.options.id, '{$type}')";
            }
        }

        $view->registerJs($script);
    }

    /**
     * Register Noty plugin
     */
    public function registerPlugin()
    {
        $view = $this->getView();
        $js = <<< JS
        function generateNoty(options) {
            var n = noty(options);
            return n;
        }
JS;

        $view->registerJs($js);
    }

    /**
     * Register required assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        $asset = NotyAsset::register($view);
        $asset->animateCss = $this->registerAnimateCss;
        $asset->fontAwesomeCss = $this->enableIcon && $this->registerFontAwesomeCss;
    }

    /**
     * Verify type, if not return defalut type
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