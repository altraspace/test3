<?php

use yii\db\Migration;

/**
 * Class m230207_134120_add_new_column_newtwork_location_to_device_table
 */
class m230207_134120_add_new_column_newtwork_location_to_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('device', 'dc_network', $this->string());
        $this->addColumn('device', 'asn_network', $this->string());
        $this->addColumn('device', 'asn_route', $this->string());
        $this->addColumn('device', 'location_latitude', $this->string());
        $this->addColumn('device', 'location_longitude', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230207_134120_add_new_column_newtwork_location_to_device_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230207_134120_add_new_column_newtwork_location_to_device_table cannot be reverted.\n";

        return false;
    }
    */
}
