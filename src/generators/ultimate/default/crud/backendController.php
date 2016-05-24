<?php
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperCrud->getNsByPattern(basename(__FILE__, '.php'), $generator->helperCrud->getControllerClass(true)) ?>;

use yii\filters\VerbFilter;
use <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', false, true, true) ?>;
use <?= $generator->helperComponent->getHandlerClass('backendSearchHandler', false, true, true) ?>;

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
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', true) ?>();

        return $this->render('view', [
            'model' => $handler->read(<?= $actionParams ?>),
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

        return $this->render('create', [
            'model' => $handler->create(\Yii::$app->request->post()),
        ]);

    }

    /**
     * Updates an existing <?= $generator->helperModel->getModelClass(true) ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', true) ?>();

        return $this->render('update', [
            'model' => $handler->update(<?= $actionParams ?>, \Yii::$app->request->post()),
        ]);
    }

    /**
     * Deletes an existing <?= $generator->helperModel->getModelClass(true) ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('backendCrudHandler', true) ?>();

        return $handler->delete(<?= $actionParams ?>, function($result) {
                return $this->redirect(['index']);
            });
    }
}
