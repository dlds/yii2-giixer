<?php
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperCrud->getNsByPattern(basename(__FILE__, '.php'), $generator->helperCrud->getControllerClass(true)) ?>;

use yii\filters\VerbFilter;

/**
 * <?= $generator->helperCrud->getControllerClass(true) ?> implements the CRUD actions for <?= $generator->helperModel->getModelClass(true) ?> model.
 */
class <?= $generator->helperCrud->getControllerClass(true) ?> extends <?= $generator->helperCrud->getControllerParentClass(false, true) ?> {

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
        $filter = new ToolsCompassValueSearchHandler(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'filter' => $filter,
        ]);
    }

    /**
     * Displays a single <?= $generator->helperModel->getModelClass(true) ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        $handler = new ToolsCompassValueCrudHandler(<?= $actionParams ?>);

        return $this->render('view', [
            'model' => $handler->read(),
        ]);
    }

    /**
     * Creates a new <?= $generator->helperModel->getModelClass(true) ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $handler = new ToolsCompassValueCrudHandler();

        return $this->render('create', [
            'model' => $handler->create(),
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
        $handler = new ToolsCompassValueCrudHandler(<?= $actionParams ?>);

        return $this->render('update', [
            'model' => $handler->update(),
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
        $handler = new ToolsCompassValueCrudHandler(<?= $actionParams ?>);

        $handler->delete();

        return $this->redirect(['index']);
    }
}
