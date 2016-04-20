<?php
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperCrud->getNsByPattern(basename(__FILE__, '.php'), $generator->helperCrud->getControllerClass(true)) ?>;

use <?= $generator->helperComponent->getHandlerClass('frontendCrudHandler', false, true, true) ?>;
use <?= $generator->helperComponent->getHandlerClass('frontendSearchHandler', false, true, true) ?>;

/**
 * <?= $generator->helperCrud->getControllerClass(true) ?> implements the CRUD actions for <?= $generator->helperModel->getModelClass(true) ?> model.
 */
class <?= $generator->helperCrud->getControllerClass(true) ?> extends <?= $generator->helperCrud->getControllerParentClass(false, true, \Yii::$app->getModule('gii')->controllerFrontendBaseClass) ?> {

    /**
     * Lists all <?= $generator->helperModel->getModelClass(true) ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
        $handler = new <?= $generator->helperComponent->getHandlerClass('frontendSearchHandler', true) ?>(\Yii::$app->request->queryParams);

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
        $handler = new <?= $generator->helperComponent->getHandlerClass('frontendCrudHandler', true) ?>(<?= $actionParams ?>);

        return $this->render('view', [
            'model' => $handler->read(),
        ]);
    }

}
