<?php

namespace dlds\giixer\components\traits;

use yii\helpers\ArrayHelper;

/**
 * Answer request Controller Trait
 */
trait GxAnswerRequestTrait
{

    /**
     * Tries ajax response when request is also ajax
     * otherwise sends normal response
     * @param array|string $view [ajaxView, originView] or 'originPart/ajaxPart'
     * @param array $params
     * @return string
     */
    public function answer($view, $params, $condition)
    {
        return $this->render($this->_answerView($view, $condition), $params);
    }

    /**
     * Tries ajax response when request is also ajax
     * otherwise sends normal response
     * @param array|string $view [ajaxView, originView] or 'originPart/ajaxPart'
     * @param array $params
     * @return string
     */
    public function ajaxAnswer($view, $params, $condition)
    {
        return $this->renderAjax($this->_answerView($view, $condition), $params);
    }

    /**
     * Tries ajax response when request is also ajax
     * otherwise sends normal response
     * @param array|string $view [ajaxView, originView] or 'originPart/ajaxPart'
     * @param array $params
     * @return string
     */
    public function tryAjaxAnswer($view, $params, $ajxCondition = true)
    {
        if (!\Yii::$app->request->isAjax) {

            // e.g. 'login'
            return $this->render($this->_answerView($view, false), $params);
        }

        // e.g. 'login/_form'
        return $this->ajaxAnswer($view, $params, $ajxCondition);
    }

    /**
     * Parses proper view path
     * @param array|string $definition
     * @return string
     */
    protected function _answerView($definition, $condition)
    {
        if (!is_array($definition)) {

            // ['ajax-full-path', 'standart-full-path']
            $views = explode('#', $definition);

            // if # is not occured try another pattern
            if (count($views) != 2) {

                // ['standart-full-path//ajax-view-path']
                $parts = explode('//', $definition);

                $ajax = str_replace('//', '/', $definition);

                $views = [
                    $ajax,
                    ArrayHelper::getValue($parts, 0, $ajax),
                ];
            }
        }

        return $condition ? $views[0] : $views[1];
    }

}
