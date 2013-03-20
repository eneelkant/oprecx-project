<?php

class m130320_064409_insert_forms extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->insert('{{users}}', array(
            'id' => 6,
            //'name' => 'varchar(32) NOT NULL',
            'email' => 'frankenstein@ui.ac.id',
            'token' => crypt('password'),
            'full_name' => 'frankenstein',
            //'img_id' => 'int unsigned DEFAULT NULL',
            //'link' => 'varchar(256) DEFAULT NULL',
        ));
        
        $this->insert('{{division_choices}}', array(
            'div_id' => 1,
            'user_id' => 6,
        ));
        
        $this->insert('{{forms}}', array(
            'form_id' => 1,
            'org_id' => 1,
            'name' => 'Informasi Pribadi',
            //'weight' => 'int NOT NULL DEFAULT 0',
            //'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        //*/
        $this->insert('{{division_forms}}', array(
            'div_id' => 1,
            'form_id' => 1,
        ));
        $this->insert('{{division_forms}}', array(
            'div_id' => 2,
            'form_id' => 1,
        ));

        $this->insert('{{form_fields}}', array(
            'field_id' => 1,
            'form_id' => 1,
            'name' => 'Fakultas',
            'type' => 'select',
            'desc' => '',
            //'weight' => 'int NOT NULL DEFAULT 0',
            'required' => 1,
            'options' => '{"options":["FK","FKG","FMIPA","FT"]}',
            //'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->insert('{{form_fields}}', array(
            'field_id' => 2,
            'form_id' => 1,
            'name' => 'Jurusan',
            'type' => 'text',
            'desc' => 'Jurusan kamu sekarang',
            //'weight' => 'int NOT NULL DEFAULT 0',
            'required' => 1,
            'options' => '{"maxlen":64}',
            //'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->insert('{{form_fields}}', array(
            'field_id' => 3,
            'form_id' => 1,
            'name' => 'Angkatan',
            'type' => 'number',
            'desc' => 'Tahun angkatan masuk',
            //'weight' => 'int NOT NULL DEFAULT 0',
            'required' => 1,
            //'options' => '',
            //'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));

        /*
        $this->insert('{{form_values}}', array(
            'value_id' => 'int unsigned NOT NULL',
            'field_id' => 'int unsigned NOT NULL',
            'user_id' => 'int unsigned NOT NULL',
            'value' => 'text',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',
            'PRIMARY KEY (`value_id`)',
            'KEY `field_id` (`field_id`)',
            'KEY `user_id` (`user_id`)',
            'CONSTRAINT `form_value_fk1` FOREIGN KEY (`field_id`) REFERENCES `{{form_fields}}` (`field_id`)',
            'CONSTRAINT `form_value_fk2` FOREIGN KEY (`user_id`) REFERENCES `{{users}}` (`id`)',
        ));
         * 
         */
    }

    public function safeDown() {
        $this->delete('{{form_fields}}', 'field_id IN (1,2,3)');
        $this->delete('{{forms}}', 'form_id = 1');
        $this->delete('{{division_forms}}');
        $this->delete('{{division_choices}}', 'user_id = 6');
        $this->delete('{{users}}', 'id=6');
    }

}