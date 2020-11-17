<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */


namespace enupal\presence\migrations;

use craft\db\Migration;
use Craft;

/**
 * Installation Migration
 */
class Install extends Migration
{
    const SESSION_TABLE = "{{%presence_session}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTables();
        $this->createIndexes();
        $this->addForeignKeys();

        return true;
    }

    /**
     * Creates the tables.
     *
     * @return void
     */
    protected function createIndexes()
    {
        $this->createIndex(
            $this->db->getIndexName(
                self::SESSION_TABLE,
                ['elementId', 'userId'],
                false, true
            ),
            self::SESSION_TABLE,
            ['elementId', 'userId'],
            true
        );

        $this->createIndex(
            $this->db->getIndexName(
                self::SESSION_TABLE,
                'lastDateAlive',
                false, true
            ),
            self::SESSION_TABLE,
            'lastDateAlive',
            false
        );
    }

    /**
     * Creates the tables.
     *
     * @return void
     */
    protected function createTables()
    {
        $this->createTable(self::SESSION_TABLE, [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'elementId' => $this->integer(),
            'lastDateAlive' => $this->dateTime()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);
    }

    /**
     * Adds the foreign keys.
     *
     * @return void
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName(
                self::SESSION_TABLE, 'elementId'
            ),
            self::SESSION_TABLE, 'elementId',
            '{{%elements}}', 'id', 'CASCADE', null
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName(
                self::SESSION_TABLE, 'userId'
            ),
            self::SESSION_TABLE, 'userId',
            '{{%elements}}', 'id', 'CASCADE', null
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTableIfExists(self::SESSION_TABLE);

        return true;
    }
}