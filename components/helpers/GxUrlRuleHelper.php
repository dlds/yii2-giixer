<?php

namespace dlds\giixer\components\helpers;

use yii\web\UrlRule;

class GxUrlRuleHelper extends UrlRule {

    /**
     * @var string connection ID
     */
    public $connectionID = 'db';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->name === null)
        {
            $this->name = __CLASS__;
        }

        return parent::init();
    }

    /**
     * Retrieves rule with leading slash or not based on given root parameter
     * @param string $rule given url rule
     * @param boolean $root use leading slash or not
     */
    public static function getRule($pattern, $route, $host = false, $verb = false, $mode = false)
    {
        $rule = [
            'class' => self::className(),
            'pattern' => $pattern,
            'route' => $route,
        ];

        if (false !== $host)
        {
            $rule['host'] = $host;
        }

        if (false !== $verb)
        {
            $rule['verb'] = $verb;
        }

        if (false !== $verb)
        {
            $rule['mode'] = $mode;
        }

        return $rule;
    }
}