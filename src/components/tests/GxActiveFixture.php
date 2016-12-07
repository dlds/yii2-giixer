<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\tests;

/**
 * ActiveFixture represents a fixture backed up by a [[modelClass|ActiveRecord class]] or a [[tableName|database table]].
 *
 */
class GxActiveFixture extends \yii\test\ActiveFixture
{

    /**
     * @var boolean indicates if table will be reseted
     */
    public $reset = true;

    /**
     * @var boolean indicates if foreign keys will be checked
     */
    public $checkIntegrity = true;

    /**
      /**
     * Loads the fixture.
     *
     * The default implementation will first clean up the table by calling [[resetTable()]].
     * It will then populate the table with the data returned by [[getData()]].
     *
     * If you override this method, you should consider calling the parent implementation
     * so that the data returned by [[getData()]] can be populated into the table.
     */
    public function load()
    {
        if ($this->reset) {
            $this->resetTable();
        }

        $this->data = [];
        $table = $this->getTableSchema();
        foreach ($this->getData() as $alias => $row) {
            $primaryKeys = $this->db->schema->insert($table->fullName, $row);
            $this->data[$alias] = array_merge($row, $primaryKeys);
        }
    }

    /**
     * Returns the fixture data.
     *
     * The default implementation will try to return the fixture data by including the external file specified by [[dataFile]].
     * The file should return an array of data rows (column name => column value), each corresponding to a row in the table.
     *
     * If the data file does not exist, an empty array will be returned.
     *
     * @return array the data rows to be inserted into the database table.
     */
    protected function getData()
    {
        if ($this->dataFile === null) {
            $class = new \ReflectionClass($this);
            $dataFile = $this->dirData($class) . '/' . str_replace('_', '/', $this->getTableSchema()->fullName) . '.php';
            
            return is_file($dataFile) ? require($dataFile) : parent::getData();
        } else {
            return parent::getData();
        }
    }

    /**
     * Removes all existing data from the specified table and resets sequence number to 1 (if any).
     * This method is called before populating fixture data into the table associated with this fixture.
     */
    protected function resetTable()
    {
        $table = $this->getTableSchema();

        if (!$this->checkIntegrity) {
            $this->truncateTable($table);
        } else {
            $this->db->createCommand()->delete($table->fullName)->execute();
            if ($table->sequenceName !== null) {
                $this->db->createCommand()->resetSequence($table->fullName, 1)->execute();
            }
        }
    }

    /**
     * Truncates table
     * ---
     * Ignores db foreign keys
     * ---
     * @param array $fixtures
     */
    protected function truncateTable(\yii\db\TableSchema $table)
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();
        $this->db->createCommand()->truncateTable($table->fullName)->execute();
        $this->db->createCommand()->checkIntegrity(true)->execute();
    }

    /**
     * Retreives data dir path
     */
    protected function dirData(\ReflectionClass $class)
    {
        return dirname($class->getFileName());
    }
}
