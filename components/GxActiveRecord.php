<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\giixer\components;

use yii\db\ActiveRecord;

/**
 * GxActiveRecord is the base class for classes representing relational data in terms of objects.
 *

 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */
class GxActiveRecord extends ActiveRecord {

    /**
     * Retrieves models representing column
     */
    public function __toString()
    {
        return (string) $this->representingColumn();
    }

    /**
     * Retrieves model representing column
     */
    protected function representingColumn()
    {
        return $this->primaryKey;
    }

}
