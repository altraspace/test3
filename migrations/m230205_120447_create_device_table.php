<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%device}}`.
 */
class m230205_120447_create_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%device}}', [
            'id' => $this->primaryKey(),
            'ip_address' => $this->string()->notNull()->unique(),
            'hostname' => $this->string(),
            'serial' => $this->string(),
            'ping_status' => $this->string(),
            'ping_output' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%device}}');
    }
}
