<?php
declare(strict_types=1);

/**
 * This file is part of the Phalcon Migrations.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Phalcon\Migrations\Mvc\Model\Migration\TableAware;

use DirectoryIterator;
use InvalidArgumentException;
use Phalcon\Mvc\Model\Migration as ModelMigration;
use Phalcon\Db\Exception as DbException;

class ListTablesDb implements ListTablesInterface
{
    /**
     * Get table names with prefix for running migration
     *
     * @param string $tablePrefix
     * @param DirectoryIterator $iterator
     * @return string
     * @throws DbException
     */
    public function listTablesForPrefix($tablePrefix, DirectoryIterator $iterator = null)
    {
        if (empty($tablePrefix)) {
            throw new InvalidArgumentException("Parameters weren't defined in " . __METHOD__);
        }

        $modelMigration = new ModelMigration();
        $connection = $modelMigration->getConnection();

        $tablesList = $connection->listTables();
        if (empty($tablesList)) {
            return '';
        }

        $strlen = strlen($tablePrefix);
        foreach ($tablesList as $key => $value) {
            if (substr($value, 0, $strlen) != $tablePrefix) {
                unset($tablesList[$key]);
            }
        }

        if (empty($tablesList)) {
            throw new DbException("Specified table prefix doesn't match with any table name");
        }

        return implode(',', $tablesList);
    }
}
