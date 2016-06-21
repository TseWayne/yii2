<?php

use yii\db\Migration;
use yii\db\Schema;

class m160415_032918_procurement extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%procurement}}', [
              'id' => Schema::TYPE_BIGPK,
              'danwei' => Schema::TYPE_STRING . '(255) NOT NULL',
              'shixiang'  => Schema::TYPE_TEXT . ' NOT NULL',
              'yaoqiu' => Schema::TYPE_TEXT . ' NOT NULL',
              'liyou' => Schema::TYPE_TEXT . ' NOT NULL',
              'zonge' => Schema::TYPE_DECIMAL .'(10,2) NOT NULL DEFAULT 0.00', 
              'laiyuan_yusuan' => Schema::TYPE_DECIMAL .'(10,2) NOT NULL DEFAULT 0.00', 
              'laiyuan_feishui' => Schema::TYPE_DECIMAL .'(10,2) NOT NULL DEFAULT 0.00', 
              'laiyuan_shangji' => Schema::TYPE_DECIMAL .'(10,2) NOT NULL DEFAULT 0.00', 
              'laiyuan_qita' => Schema::TYPE_DECIMAL .'(10,2) NOT NULL DEFAULT 0.00', 
              'jingbanren' => Schema::TYPE_STRING . '(20) NOT NULL',
              'dianhua' => Schema::TYPE_STRING . '(15) NOT NULL',
              'starttime' => Schema::TYPE_INTEGER . '(11) NOT NULL',
              'edittime' => Schema::TYPE_INTEGER . '(11) NOT NULL',
              'userid' => Schema::TYPE_INTEGER . '(11) NOT NULL',
              'username' => Schema::TYPE_STRING . '(255) NOT NULL',
              'xiangmulei' => Schema::TYPE_STRING . '(255) NOT NULL',
              'caigoufangshi' => Schema::TYPE_STRING . '(255) NOT NULL',
              'sign_zhuguan' => Schema::TYPE_TEXT . ' NOT NULL',
              'sign_zhuguan_is' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT 0",
              'sign_zhuguan_time' => Schema::TYPE_INTEGER . '(11) NOT NULL',
              'sign_jing' => Schema::TYPE_TEXT . ' NOT NULL',
              'sign_jing_is' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT 0",
              'sign_jing_time' => Schema::TYPE_INTEGER . '(11) NOT NULL',
              'sign_fuze' => Schema::TYPE_TEXT . ' NOT NULL',
              'sign_fuze_is' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT 0",
              'sign_fuze_time' => Schema::TYPE_INTEGER . '(11) NOT NULL',
              'endtime' => Schema::TYPE_INTEGER . '(11) NOT NULL',
              'daili' => Schema::TYPE_STRING . '(255) NOT NULL',
              'status' => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT 0",
        ],$tableOptions);
        
        $this->createIndex('userid', '{{%procurement}}', 'userid');
        $this->createIndex('status', '{{%procurement}}', 'status');
    }

    public function down()
    {
        echo "m160415_032918_procurement cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
