<?php

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \dlds\giixer\generators\ultimate\Generator */

echo "<?php\n";
?>

namespace <?= $generator->helperModel->getNsByPattern(basename(__FILE__, '.php'), $generator->helperModel->getSearchClass(true)) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?= $generator->helperModel->getSearchParentClass(basename(__FILE__, '.php'), false) ?>;

/**
 * <?= $generator->helperModel->getSearchClass(true) ?> represents the model behind the search form about `<?= $generator->helperModel->getModelClass(true) ?>`.
 */
class <?= $generator->helperModel->getSearchClass(true) ?> extends <?= $generator->helperModel->getSearchParentClass(basename(__FILE__, '.php'), true) ?> {

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
        $query = <?= $generator->helperModel->getSearchParentClass(basename(__FILE__, '.php'), true) ?>::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->sortData($dataProvider);

        if (!($this->load($params) && $this->validate()))
        {
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
