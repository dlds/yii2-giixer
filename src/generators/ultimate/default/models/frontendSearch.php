<?php

use dlds\giixer\generators\ultimate\helpers\ModelHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= ModelHelper::ns($generator->helperModel->getClass(ModelHelper::RK_SEARCH_FE)) ?>;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>;

/**
 * <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_SEARCH_FE)) ?> represents the model behind the searching of `<?= ModelHelper::root($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>`.
 */
class <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_SEARCH_FE)) ?> extends <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?> 
{

    /**
     * @var array allowd sorting attrs
     */
    public $sortAttrs = [
        '<?= implode("',\n         '", $generator->filterSortAttrs($attributes)) ?>'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = <?= ModelHelper::basename($generator->helperModel->getClass(ModelHelper::RK_MODEL_CM)) ?>::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->sortData($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            $this->generalQuery($query, false);

            return $dataProvider;
        }

        $this->generalQuery($query);

        $this->additionalQuery($query);

        return $dataProvider;
    }

    /**
     * Sets sort data
     * @param ActiveDataProvider $dataProvider
     */
    protected function sortData(ActiveDataProvider &$dataProvider)
    {
        $dataProvider->setSort([
            'attributes' => $this->getSortAttrs(),
            'defaultOrder' => static::getDefaultOrder(),
        ]);
    }

    /**
     * General query
     * @param type $query
     */
    protected function generalQuery(\yii\db\ActiveQuery &$query, $loaded = true)
    {
        // add query wich will be processed everytime
    }

    /**
     * Additional query
     * @param type $query
     */
    protected function additionalQuery(\yii\db\ActiveQuery &$query)
    {
        <?= implode("\n        ", $conditions) ?>
    }

    /**
     * Retrieves sorting attrs
     * @return array
     */
    protected function getSortAttrs()
    {
        $default = static::getDefaultOrder();

        return \yii\helpers\ArrayHelper::merge($this->sortAttrs, array_keys($default));
    }

    /**
     * Retrieves default sort order
     * @return array
     */
    public static function getDefaultOrder()
    {
        return [static::tableName().'.<?= ($generator->generateSortableBehavior) ? $generator->sortableColumnAttribute : $primaryKey ?>' => SORT_DESC];
    }
}
