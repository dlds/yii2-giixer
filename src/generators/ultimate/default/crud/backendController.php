<?php
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperCrud->getNsByPattern(basename(__FILE__, '.php'), $generator->helperCrud->getControllerClass(true)) ?>;

use yii\filters\VerbFilter;
use dlds\giixer\components\helpers\GxFlashHelper;
<?php if($generator->generateGalleryBehavior): ?>
use <?= $generator->helperComponent->getHelperClass('imageHelper', false, true, true) ?>;
<?php endif; ?>
use <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', false, true, true) ?>;
use <?= $generator->helperComponent->getHandlerClass('backendSearchHandler', false, true, true) ?>;
use <?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', false, true, true) ?>;
<?php if($generator->generateSortableBehavior): ?>
use <?= $generator->helperModel->getModelClass(false) ?>;
<?php endif; ?>

/**
 * <?= $generator->helperCrud->getControllerClass(true) ?> implements the CRUD actions for <?= $generator->helperModel->getModelClass(true) ?> model.
 */
class <?= $generator->helperCrud->getControllerClass(true) ?> extends <?= $generator->helperCrud->getControllerParentClass(false, true, \Yii::$app->getModule('gii')->getBaseClass($generator->helperCrud->getControllerClass(true), \dlds\giixer\Module::BASE_CONTROLLER_BACKEND)) ?> {

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
            'modelClass' => <?= $generator->helperModel->getModelClass(true) ?>::className(),
        ];
<?php endif; ?>
<?php if($generator->generateGalleryBehavior): ?>

        $actions['api-gallery'] = [
            'class' => \dlds\galleryManager\GalleryManagerAction::className(),
            'types' => [
                <?= $generator->helperComponent->getHelperClass('imageHelper', true) ?>::getType() => <?= $generator->helperComponent->getHelperClass('imageHelper', true) ?>::modelClass(),
            ]
        ];
<?php endif; ?>

        return $actions;
    }
<?php endif; ?>

    /**
     * Lists all <?= $generator->helperModel->getModelClass(true) ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendSearchHandler', true) ?>(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchHandler' => $handler,
        ]);
    }

    /**
     * Displays a single <?= $generator->helperModel->getModelClass(true) ?> model.
     * @param integer $id primary key
     * @return mixed
     */
    public function actionView($id)
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', true) ?>();

        $evt = $handler->read($id);

        if (!$evt->isRead())
        {
            return $handler->notFoundFallback();
        }

        return $this->render('view', [
                'model' => $evt->model,
        ]);
    }

    /**
     * Creates a new <?= $generator->helperModel->getModelClass(true) ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', true) ?>();

        $evt = $handler->create(\Yii::$app->request->post());

        if ($evt->isCreated())
        {
            GxFlashHelper::set(GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::message(GxFlashHelper::MESSAGE_CREATE_SUCCESS));

            return $this->redirect(<?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', true) ?>::index());
        }

        return $this->render('create', [
                'model' => $evt->model,
        ]);
    }

    /**
     * Updates an existing <?= $generator->helperModel->getModelClass(true) ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id primary key
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', true) ?>();

        $evt = $handler->update($id, \Yii::$app->request->post());

        if ($evt->isUpdated())
        {
            GxFlashHelper::set(GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::message(GxFlashHelper::MESSAGE_UPDATE_SUCCESS));
        }

        return $this->render('update', [
                'model' => $evt->model,
        ]);
    }

    /**
     * Deletes an existing <?= $generator->helperModel->getModelClass(true) ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id primary key
     * @return mixed
     */
    public function actionDelete($id)
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', true) ?>();

        $evt = $handler->delete($id);

        if ($evt->isDeleted())
        {
            GxFlashHelper::set(GxFlashHelper::FLASH_SUCCESS, GxFlashHelper::message(GxFlashHelper::MESSAGE_DELETE_SUCCESS));

            return $this->redirect(<?= $generator->helperComponent->getHelperClass('backendUrlRouteHelper', true) ?>::index());
        }

        GxFlashHelper::set(GxFlashHelper::FLASH_ERROR, GxFlashHelper::message(GxFlashHelper::MESSAGE_DELETE_FAIL));

        return $handler->notProcessableFallback();
    }
}
