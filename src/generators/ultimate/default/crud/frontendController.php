<?php

use dlds\giixer\generators\ultimate\helpers\CrudHelper;
use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= CrudHelper::ns($generator->helperCrud->getClass(CrudHelper::RK_CONTROLLER_FE)) ?>;

use <?= $generator->helperComponent->getClass(CrudHelper::RK_HANDLER_CRUD_FE) ?>;
use <?= $generator->helperComponent->getClass(CrudHelper::RK_HANDLER_SEARCH_FE) ?>;

/**
 * <?= CrudHelper::basename($generator->helperCrud->getClass(CrudHelper::RK_CONTROLLER_FE)) ?> implements the CRUD actions for <?= ModelHelper::root($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?> model.
 */
class <?= CrudHelper::basename($generator->helperCrud->getClass(CrudHelper::RK_CONTROLLER_FE)) ?> extends <?= CrudHelper::root($generator->helperCrud->getParentClass(CrudHelper::RK_CONTROLLER_FE)) ?> 
{

    /**
     * Lists all <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
        $handler = new <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_SEARCH_FE)) ?>(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchHandler' => $handler,
        ]);
    }

    /**
     * Displays a single <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> model.
     * @param integer $id primary key
     * @return mixed
     */
    public function actionView($id)
    {
        $handler = new <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_FE)) ?>();

        $evt = $handler->read($id);

        if (!$evt->isRead()) {
            return $handler->notFoundFallback();
        }

        return $this->render('view', [
                'model' => $evt->model,
        ]);
    }

}
