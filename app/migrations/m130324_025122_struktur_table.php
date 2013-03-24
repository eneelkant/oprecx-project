<?php

class m130324_025122_struktur_table extends CDbMigration
{
	public function up()
	{
            $this->addColumn('{{organizations}}', 'updated', 'DATETIME');
            $this->addColumn('{{users}}', 'password', 'VARCHAR(255)');
	}

	public function down()
	{
            $this->renameColumn($table, $name, $newName);
		echo "m130324_025122_struktur_table does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}