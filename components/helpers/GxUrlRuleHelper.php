<?php

namespace dlds\giixer\components\helpers;

use yii\web\UrlRule;

class GxUrlRuleHleper extends UrlRule {

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
        $class = $this->name;

        $rule = [
            'class' => $class::classname(),
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