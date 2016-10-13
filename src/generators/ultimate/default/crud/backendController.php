<?php

use dlds\giixer\generators\ultimate\helpers\CrudHelper;
use dlds\giixer\generators\ultimate\helpers\ComponentHelper;
use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= CrudHelper::ns($generator->helperCrud->getClass(CrudHelper::RK_CONTROLLER_BE)) ?>;

use yii\filters\VerbFilter;
use dlds\giixer\components\helpers\GxFlashHelper;
<?php if($generator->generateSortableBehavior): ?>
use <?= $generator->helperModel->getClass(CrudHelper::RK_MODEL_CM) ?>;
<?php endif; ?>
<?php if($generator->generateGalleryBehavior): ?>
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_IMAGE) ?>;
<?php endif; ?>
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_BE) ?>;
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_SEARCH_BE) ?>;
use <?= $generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE) ?>;

/**
 * <?= CrudHelper::basename($generator->helperCrud->getClass(CrudHelper::RK_CONTROLLER_BE)) ?> implements the CRUD actions for <?= ModelHelper::root($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?> model.
 */
class <?= CrudHelper::basename($generator->helperCrud->getClass(CrudHelper::RK_CONTROLLER_BE)) ?> extends <?= CrudHelper::root($generator->helperCrud->getParentClass(CrudHelper::RK_CONTROLLER_BE)) ?> 
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
<?php if($generator->hasControllerExternalActions()): ?>

    /**
     * Retrieves external actions linked to this controller
     * @param array $params additional route params
     * @return array route
     */
    public function actions()
    {
        $actions = [];
<?php if($generator->generateSortableBehavior): ?>

        $actions['sort'] = [
            'class' => \dlds\sortable\components\Action::className(),
            'modelClass' => <?= ModelHelper::basename($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?>::className(),
        ];
<?php endif; ?>
<?php if($generator->generateGalleryBehavior): ?>

        $actions['gallery'] = [
            'class' => \dlds\galleryManager\GalleryManagerAction::className(),
            'types' => [
                <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_IMAGE)) ?>::getType() => <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_IMAGE)) ?>::modelClass(),
            ]
        ];
<?php endif; ?>

        return $actions;
    }
<?php endif; ?>

    /**
     * Lists all <?= ModelHelper::root($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
        $handler = new <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_SEARCH_BE)) ?>(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchHandler' => $handler,
        ]);
    }

    /**
     * Displays a single <?= ModelHelper::root($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?> model.
     * @param integer $id primary key
     * @return mixed
     */
    public function actionView($id)
    {
        $handler = new <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_BE)) ?>();

        $evt = $handler->read($id);

        if (!$evt->isRead()) {
            return $handler->notFoundFallback();
        }

        return $this->render('view', [
                'model' => $evt->model,
        ]);
    }

    /**
     * Creates a new <?= ModelHelper::root($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $handler = new <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_BE)) ?>();

        $evt = $handler->create(\Yii::$app->request->post());

        if ($evt->isCreated()) {
            GxFlashHelper::set(GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::message(GxFlashHelper::MESSAGE_CREATE_SUCCESS));

            return $this->redirect(<?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) ?>::index());
        }

        return $this->render('create', [
                'model' => $evt->model,
        ]);
    }

    /**
     * Updates an existing <?= ModelHelper::root($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id primary key
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $handler = new <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_BE)) ?>();

        $evt = $handler->update($id, \Yii::$app->request->post());

        if ($evt->isUpdated()) {
            GxFlashHelper::set(GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::message(GxFlashHelper::MESSAGE_UPDATE_SUCCESS));
        }

        return $this->render('update', [
                'model' => $evt->model,
        ]);
    }

    /**
     * Deletes an existing <?= ModelHelper::root($generator->helperModel->getClass(CrudHelper::RK_MODEL_CM)) ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id primary key
     * @return mixed
     */
    public function actionDelete($id)
    {
        $handler = new <?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HANDLER_CRUD_BE)) ?>();

        $evt = $handler->delete($id);

        if ($evt->isDeleted()) {
            GxFlashHelper::set(GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::message(GxFlashHelper::MESSAGE_DELETE_SUCCESS));

            return $this->redirect(<?= ComponentHelper::basename($generator->helperComponent->getClass(ComponentHelper::RK_HELPER_URL_ROUTE_BE)) ?>::index());
        }

        GxFlashHelper::set(GxFlashHelper::FLASH_ERROR, GxFlashHelper::message(GxFlashHelper::MESSAGE_DELETE_FAIL));

        return $handler->notProcessableFallback();
    }
}
