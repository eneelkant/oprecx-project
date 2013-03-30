<?php

class m130324_025122_struktur_table extends CDbMigration
{
	public function up()
	{
            $this->addColumn('{{organizations}}', 'updated', 'DATETIME DEFAULT NULL AFTER `created`');
            $this->addColumn('{{users}}', 'password', 'VARCHAR(255) DEFAULT NULL AFTER email');
            $this->addColumn('{{users}}', 'last_login', 'DATETIME DEFAULT NULL AFTER created');
            $this->update('{{users}}', array('password' => crypt('123'), 'full_name' => 'Frankstein Bennington'), 'id=:id', array('id' => 6));
            
            $this->addColumn('{{division_choices}}', 'weight', 'int NOT NULL DEFAULT 0');
            //$this->addColumn('{{division_choices}}', 'weight', 'int NOT NULL DEFAULT 0');
            //$this->addColumn('{{users}}', 'updated', 'DATETIME DEFAULT NULL AFTER email');
            // ALTER TABLE `oprecx_division_choices` ADD `weight` INT NOT NULL DEFAULT '0' AFTER `user_id` ;
	}

	public function down()
	{
            $this->dropColumn('{{organizations}}', 'updated');
            $this->dropColumn('{{users}}', 'password');
            $this->dropColumn('{{users}}', 'last_login');
            $this->dropColumn('{{division_choices}}', 'weight');
            //$this->renameColumn($table, $name, $newName);
		//echo "m130324_025122_struktur_table does not support migration down.\n";
		//return false;
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