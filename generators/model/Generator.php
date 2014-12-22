<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\generators\model;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class Generator extends \yii\gii\generators\model\Generator {

    public $files = array(
        'common/models/db/base/' => 'model',
        'common/models/db/' => 'commonModel',
        'backend/models/db/' => 'backendModel',
        'frontend/models/db/' => 'frontendModel',
    );

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName)
        {
            $className = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $className,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$className]) ? $relations[$className] : [],
            ];

            if ($this->files)
            {
                foreach ($this->files as $ns => $tmpl)
                {
                    $files[] = new CodeFile(
                            Yii::getAlias('@' . str_replace('\\', '/', $ns)) . '/' . $className . '.php', $this->render(sprintf('%s.php', $tmpl), $params)
                    );
                }
            }
            else
            {
                $files[] = new CodeFile(
                        Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $className . '.php', $this->render('model.php', $params)
                );
            }
        }

        return $files;
    }

}
