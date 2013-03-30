<?php

class m130330_043559_redesign extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        
        $this->createTable('{{org_elms}}', array(
            'elm_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'org_id' => 'int unsigned NOT NULL',
            'name' => 'varchar(255) NOT NULL',
            'weight' => 'int DEFAULT 0',
            //'desc' => 'text DEFAULT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',
            
            'PRIMARY KEY (`elm_id`)',
            'KEY `org_id` (`org_id`)',
            'CONSTRAINT `org_elms_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `{{organizations}}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));
        
        
        $this->dropForeignKey('forms_ibfk_1', '{{forms}}');
        $this->dropForeignKey('form_field_fk1', '{{form_fields}}');
        $this->dropTable('{{division_forms}}');
        
        $this->dropColumn('{{forms}}', 'org_id');
        $this->dropColumn('{{forms}}', 'name');
        $this->dropColumn('{{forms}}', 'weight');
        $this->dropColumn('{{forms}}', 'created');
        $this->renameColumn('{{forms}}', 'form_id', 'elm_id');
        
        $this->insert('{{org_elms}}', array(
            'elm_id' => 1,
            'org_id' => 1,
            'name' => 'Informasi Pribadi',
        ));

        
        $this->addForeignKey('forms_ibfk_1', '{{forms}}', 'elm_id', '{{org_elms}}', 'elm_id');
        $this->addForeignKey('form_field_fk1', '{{form_fields}}', 'form_id', '{{forms}}', 'elm_id');
        
        
        $this->createTable('{{division_elms}}', array(
            'div_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'elm_id' => 'int unsigned NOT NULL',
            
            'PRIMARY KEY (`div_id`,`elm_id`)',
            'KEY `division_elm_fk2` (`elm_id`)',
            'CONSTRAINT `division_elm_fk1` FOREIGN KEY (`div_id`) REFERENCES `{{divisions}}` (`div_id`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT `division_elm_fk2` FOREIGN KEY (`elm_id`) REFERENCES `{{org_elms}}` (`elm_id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));
        
        $this->insert('{{division_elms}}', array(
            'div_id' => 1,
            'elm_id' => 1,
        ));
        $this->insert('{{division_elms}}', array(
            'div_id' => 2,
            'elm_id' => 1,
        ));

        
        // ORG MANAGER
        $this->createTable('{{org_admins}}', array(
            'org_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL',
            'rule' => 'varchar(32) NOT NULL',
            
            'PRIMARY KEY (`org_id`,`user_id`)',
            'KEY `org_admin_fk2` (`user_id`)',
            'CONSTRAINT `org_admin_fk1` FOREIGN KEY (`org_id`) REFERENCES `{{organizations}}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT `org_admin_fk2` FOREIGN KEY (`user_id`) REFERENCES `{{users}}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));
        
        //$this->insert('{{org_admins', $columns)
    }

    public function safeDown() {
        $this->dropTable('{{division_elms}}');
        
        $this->dropForeignKey('forms_ibfk_1', '{{forms}}');
        $this->dropTable('{{org_elms}}');
        
        $this->dropForeignKey('form_field_fk1', '{{form_fields}}');
        
        $this->addColumn('{{forms}}', 'org_id', 'int unsigned NOT NULL');
        $this->addColumn('{{forms}}', 'name', 'varchar(64) NOT NULL');
        $this->addColumn('{{forms}}', 'weight', 'int NOT NULL DEFAULT 0');
        $this->addColumn('{{forms}}', 'created', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->renameColumn('{{forms}}', 'elm_id', 'form_id');
        $this->update('{{forms}}', array(
           'name' => 'Profile Pribadi',
            'org_id' => 1,
        ));
        $this->addForeignKey('forms_ibfk_1', '{{forms}}', 'org_id', '{{organizations}}', 'id');
        $this->addForeignKey('form_field_fk1', '{{form_fields}}', 'form_id', '{{forms}}', 'form_id');
        
        $this->createTable('{{division_forms}}', array(
            'div_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'form_id' => 'int unsigned NOT NULL',
            
            'PRIMARY KEY (`div_id`,`form_id`)',
            'KEY `division_form_fk2` (`form_id`)',
            'CONSTRAINT `division_form_fk1` FOREIGN KEY (`div_id`) REFERENCES `{{divisions}}` (`div_id`) ON DELETE CASCADE ON UPDATE CASCADE',
            'CONSTRAINT `division_form_fk2` FOREIGN KEY (`form_id`) REFERENCES `{{forms}}` (`form_id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));
        $this->insert('{{division_forms}}', array(
            'div_id' => 1,
            'form_id' => 1,
        ));
        $this->insert('{{division_forms}}', array(
            'div_id' => 2,
            'form_id' => 1,
        ));
        
        $this->dropTable('{{org_admins}}');

    }

}