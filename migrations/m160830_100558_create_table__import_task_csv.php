<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 30.08.2016
 */
use yii\db\Schema;
use yii\db\Migration;

class m160830_100558_create_table__import_task_csv extends Migration
{
    public function safeUp()
    {
        $tableExist = $this->db->getTableSchema("{{%import_task_csv}}", true);
        if ($tableExist)
        {
            return true;
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%import_task_csv}}", [
            'id'                    => $this->primaryKey(),

            'created_by'            => $this->integer(),
            'updated_by'            => $this->integer(),

            'created_at'            => $this->integer(),
            'updated_at'            => $this->integer(),

            'file_path'             => $this->string(255)->notNull()->comment('The path to the import file'),

            'name'                  => $this->string(255)->comment('Name'),
            'description'           => $this->text()->comment('description'),

            'component'             => $this->string(255),
            'component_settings'    => $this->text(),

        ], $tableOptions);

        $this->createIndex('updated_by', '{{%import_task_csv}}', 'updated_by');
        $this->createIndex('created_by', '{{%import_task_csv}}', 'created_by');
        $this->createIndex('created_at', '{{%import_task_csv}}', 'created_at');
        $this->createIndex('updated_at', '{{%import_task_csv}}', 'updated_at');

        $this->createIndex('name', '{{%import_task_csv}}', 'name');

        $this->execute("ALTER TABLE {{%import_task_csv}} COMMENT = 'Tasks for CSV import';");

        $this->addForeignKey(
            'import_task_csv__created_by', "{{%import_task_csv}}",
            'created_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'import_task_csv__updated_by', "{{%import_task_csv}}",
            'updated_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey("import_task_csv__created_by", "{{%import_task_csv}}");
        $this->dropForeignKey("import_task_csv__updated_by", "{{%import_task_csv}}");

        $this->dropTable("{{%import_task_csv}}");
    }
}