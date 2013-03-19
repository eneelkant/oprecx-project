<?php

class m130319_052114_initial extends CDbMigration {
    /*
      public function up() {
      }

      public function down() {
      }
      // */

    //*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        //*
        $this->createTable('images', array(
            'img_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'file_path' => 'varchar(256) NOT NULL',
            'file_url' => 'varchar(256) NOT NULL',
            'width' => 'int NOT NULL',
            'height' => 'int NOT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY (`img_id`)',
        ));

        $this->createTable('users', array(
            'id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            //'name' => 'varchar(32) NOT NULL',
            'email' => 'varchar(256) NOT NULL',
            'token' => 'varchar(512) NOT NULL',
            'full_name' => 'varchar(256) NOT NULL',
            'img_id' => 'int unsigned DEFAULT NULL',
            'link' => 'varchar(256) DEFAULT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`id`)',
            'KEY `img_id` (`img_id`)',
            'CONSTRAINT `users_ibfk_1` FOREIGN KEY (`img_id`) REFERENCES `images` (`img_id`) ON DELETE SET NULL ON UPDATE SET NULL',
        ));

        $this->createTable('user_metas', array(
            'meta_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'user_id' => 'int unsigned NOT NULL',
            'meta_name' => 'varchar(64) NOT NULL',
            'meta_value' => 'text NOT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`meta_id`)',
            'KEY `applicant_id` (`user_id`)',
            'CONSTRAINT `user_metas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));

        $this->createTable('organizations', array(
            'id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(32) NOT NULL',
            'full_name' => 'varchar(256) NOT NULL',
            'email' => 'varchar(256) NOT NULL',
            'password' => 'varchar(512) NOT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'description' => 'text NOT NULL',
            'type' => 'varchar(16) NOT NULL',
            'scope' => 'varchar(16) NOT NULL',
            'location' => 'varchar(256) NOT NULL',
            'link' => 'varchar(256) NOT NULL',
            'img_id' => 'int unsigned DEFAULT NULL',
            'reg_time_begin' => 'datetime NOT NULL',
            'reg_time_end' => 'datetime NOT NULL',
            'visibility' => 'varchar(16) NOT NULL DEFAULT \'public\'',
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `name` (`name`)',
            'KEY `img_id` (`img_id`)',
            'CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`img_id`) REFERENCES `images` (`img_id`) ON DELETE SET NULL ON UPDATE SET NULL',
        ));
        
        $this->insert('organizations', array(
            'id' => 1,
            'name' => 'pemiraui2013',
            'full_name' => 'Pemira IKM UI 2013',
            'description' => 'Pemilihan Raya Ikatan Keluarga Mahasiswa Universitas Indonesia 2013',
            'email' => 'admin@pemira.ui.ac.id',
            'password' => crypt('password'),
            'type' => 'committee',
            'scope' => 'university',
            'location' => 'Universitas Indonesia',
            'link' => 'http://pemira.ui.ac.id/',
            'reg_time_begin' => '2013-03-10 00:00:00',
            'reg_time_end' => '2013-04-19 00:00:00',
        ));

        $this->insert('organizations', array(
            'id' => 3,
            'name' => 'bemui2013',
            'full_name' => 'BEM UI 2013',
            'description' => 'Bada Eksekutif Mahasiswa Universitas Indonesia 2013',
            'email' => 'admin@bem.ui.ac.id',
            'password' => crypt('password'), 
            'type' => 'org',
            'scope' => 'university',
            'location' => 'Universitas Indonesia',
            'link' => 'http://pemira.ui.ac.id/',
            'reg_time_begin' => '2013-03-10 00:00:00',
            'reg_time_end' => '2013-04-19 00:00:00',
        ));

        $this->createTable('organization_metas', array(
            'meta_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'org_id' => 'int unsigned NOT NULL',
            'meta_name' => 'varchar(16) NOT NULL',
            'meta_value' => 'text NOT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime NOT NULL',
            'PRIMARY KEY (`meta_id`)',
            'KEY `org_id` (`org_id`)',
            'CONSTRAINT `organization_metas_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));

        $this->createTable('divisions', array(
            'div_id' => 'int unsigned NOT NULL AUTO_INCREMENT',
            'org_id' => 'int unsigned NOT NULL',
            'name' => 'varchar(128) NOT NULL',
            'description' => 'text',
            'leader' => 'varchar(64) DEFAULT NULL',
            'max_applicant' => 'int DEFAULT NULL',
            'max_staff' => 'int DEFAULT NULL',
            'min_staff' => 'int DEFAULT NULL',
            'enabled' => 'int NOT NULL DEFAULT 1',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY (`div_id`)',
            'KEY `org_id` (`org_id`)',
            'CONSTRAINT `divisions_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));
        
        $this->insert('divisions', array(
            'div_id' => 1,
            'org_id' => 1,
            'name' => 'Departemen 1',
            'description' => 'Penjelasan Departemen',
        ));
        $this->insert('divisions', array(
            'div_id' => 2,
            'org_id' => 1,
            'name' => 'Biro 1',
            'description' => 'Penjelasan Biro',
        ));
        $this->insert('divisions', array(
            'div_id' => 3,
            'org_id' => 3,
            'name' => 'Departemen 1',
            'description' => 'Penjelasan Departemen',
        ));

        $this->createTable('division_choices', array(
            'div_id' => 'int unsigned NOT NULL',
            'user_id' => 'int unsigned NOT NULL',
            'choosed' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            
            'PRIMARY KEY (`div_id`,`user_id`)',
            'KEY `division_choice_fk2` (`user_id`)',
            'CONSTRAINT `division_choice_fk1` FOREIGN KEY (`div_id`) REFERENCES `divisions` (`div_id`)',
            'CONSTRAINT `division_choice_fk2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)',
        ));

        $this->createTable('forms', array(
            'form_id' => 'int unsigned NOT NULL',
            'org_id' => 'int unsigned NOT NULL',
            'name' => 'varchar(64) NOT NULL',
            'weight' => 'int NOT NULL DEFAULT 0',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            
            'PRIMARY KEY (`form_id`)',
            'KEY `org_id` (`org_id`)',
            'CONSTRAINT `forms_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE',
        ));
    //*/
        $this->createTable('division_forms', array(
            'div_id' => 'int unsigned NOT NULL',
            'form_id' => 'int unsigned NOT NULL',
            
            'PRIMARY KEY (`div_id`,`form_id`)',
            'KEY `division_form_fk2` (`form_id`)',
            'CONSTRAINT `division_form_fk1` FOREIGN KEY (`div_id`) REFERENCES `divisions` (`div_id`)',
            'CONSTRAINT `division_form_fk2` FOREIGN KEY (`form_id`) REFERENCES `forms` (`form_id`)',
        ));

    }

    public function safeDown() {
        $this->dropTable('division_forms');
        $this->dropTable('forms');
        $this->dropTable('division_choices');
        $this->dropTable('divisions');
        $this->dropTable('organization_metas');
        $this->dropTable('organizations');
        $this->dropTable('user_metas');
        $this->dropTable('users');
        $this->dropTable('images');
    }

    //*/
}