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
    public $resetIntegrity = true;

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
     * Removes all existing data from the specified table and resets sequence number to 1 (if any).
     * This method is called before populating fixture data into the table associated with this fixture.
     */
    protected function resetTable()
    {
        $table = $this->getTableSchema();

        if (!$this->resetIntegrity) {
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

}
