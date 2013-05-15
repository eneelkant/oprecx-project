<?php

class CurrentDbScheme extends CDbMigration
{
    private $foreign, $driver;

    public function up()
    {
        $this->foreign = true;

        $tmp = explode(':', $this->getDbConnection()->connectionString, 2);
        $this->driver = strtolower($tmp[0]);

        if ($this->driver == 'mysql')
            $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        if ($this->driver == 'sqlite')
            $this->foreign = false;

        $this->dropTableIfExists(TableNames::IMAGE);
        $this->createTable(TableNames::IMAGE, array (
            'img_id'    => 'pk',
            'file_path' => 'string NOT NULL',
            'file_url'  => 'string NOT NULL',
            'width'     => 'integer NOT NULL',
            'height'    => 'integer NOT NULL',
            'created'   => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));

        $this->tableUser();
        $this->dataUser();

        $this->tableRec();
        $this->dataRec();

        $this->tableDivision();
        $this->dataDiv();

        $this->tableForms();
        $this->dataForm();

        $this->tableWawancara();
        $this->dataWawancara();

        $this->getDbConnection()->getSchema()->resetSequence(TableNames::USER, 100);
        $this->getDbConnection()->getSchema()->resetSequence(TableNames::RECRUITMENT, 100);
        $this->getDbConnection()->getSchema()->resetSequence(TableNames::REC_ELM, 100);
        $this->getDbConnection()->getSchema()->resetSequence(TableNames::DIVISION, 100);
        $this->getDbConnection()->getSchema()->resetSequence(TableNames::FORM_FIELD, 100);
        
    }

    private function tableWawancara() {
        $this->dropTableIfExists(TableNames::INTERVIEW_SLOT);
        $this->dropTableIfExists(TableNames::INTERVIEW_USER_SLOT);

        $this->createTable(TableNames::INTERVIEW_SLOT, array(
            'elm_id' => 'integer PRIMARY KEY',
            'description' => 'text DEFAULT NULL',
            'duration' => 'integer NOT NULL DEFAULT 1800',
            'start_date' => 'date NOT NULL',
            'end_date' => 'date NOT NULL',
            'time_range' => 'text NOT NULL',
            'max_user_per_slot' => 'integer NOT NULL DEFAULT 1',
            'max_slot_per_user' => 'integer NOT NULL DEFAULT 1',
            'min_slot_per_user' => 'integer NOT NULL DEFAULT 1',
            'options' => 'text DEFAULT NULL',
        ));

        $this->createTable(TableNames::INTERVIEW_USER_SLOT, array(
            'slot_id' => 'integer NOT NULL',
            'user_id' => 'integer NOT NULL',
            'time' => 'datetime NOT NULL',
            'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',

            'PRIMARY KEY (slot_id, user_id, time)',
        ));

        if ($this->foreign) {
            $this->addForeignKey('oprecx_interview_slots_elm_id_fkey', TableNames::INTERVIEW_SLOT, 'elm_id', TableNames::REC_ELM, 'elm_id');
            $this->addForeignKey('oprecx_interview_user_slots_slot_id_fkey', TableNames::INTERVIEW_USER_SLOT, 'slot_id', TableNames::INTERVIEW_SLOT, 'elm_id');
            $this->addForeignKey('oprecx_interview_user_slots_user_id_fkey', TableNames::INTERVIEW_USER_SLOT, 'user_id', TableNames::USER, 'id');
        }
    }

    private function dataWawancara() {
        $this->insert(TableNames::REC_ELM, array (
            'elm_id' => 2,
            'rec_id' => 1,
            'name' => 'Default Slot',
        ));

        $this->insert(TableNames::INTERVIEW_SLOT, array(
            'elm_id' => 2,
            'start_date' => date('Y-m-d', time() + (86400 * 1)),
            'end_date' => date('Y-m-d', time() + (86400 * 7)),
            'time_range' => serialize(array(
                array(28800, 43200), // 08:00:00 - 12:00:00
                array(46800, 64800), // 13:00:00 - 18:00:00

            )),
            'options' => serialize(array(
                //'exclude' => array(array()),
            ))
        ));

        $this->insert(TableNames::DIVISION_ELM, array (
            'div_id' => 1,
            'elm_id' => 2,
        ));

        $this->insert(TableNames::DIVISION_ELM, array (
            'div_id' => 2,
            'elm_id' => 2,
        ));

    }

    public function getLastInsertID($seqname = '') {
        return $this->getDbConnection()->getLastInsertID($seqname);
    }

    private function tableUser()
    {
        $this->dropTableIfExists(TableNames::USER);
        $this->dropTableIfExists(TableNames::USER_META);

        $this->createTable(TableNames::USER, array (
            'id'         => 'pk',
            'email'      => 'string NOT NULL UNIQUE',
            'password'   => 'string DEFAULT NULL',
            'full_name'  => 'string NOT NULL',
            'img_id'     => 'integer DEFAULT NULL',
            'link'       => 'string DEFAULT NULL',
            'created'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'last_login' => 'datetime DEFAULT NULL',
            'updated'    => 'datetime DEFAULT NULL',

            //"FOREIGN KEY(img_id) REFERENCES {{images}} (img_id) ON DELETE SET NULL",
        ));
        //$this->createIndex('oprecx_users_users_img_id', TableNames::USER, 'img_id');

        $this->createTable(TableNames::USER_META, array (
            'user_id'    => 'integer NOT NULL',
            'meta_name'  => 'string NOT NULL',
            'meta_value' => 'text NOT NULL',
            'created'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated'    => 'datetime DEFAULT NULL',

            'PRIMARY KEY (user_id, meta_name)',
            //"FOREIGN KEY(user_id) REFERENCES {{users}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));     
        //$this->createIndex('oprecx_user_metas_user_id', TableNames::USER_META, 'user_id');

        if ($this->foreign) {
            $this->addForeignKey('oprecx_users_img_id_fkey', TableNames::USER, 'img_id', TableNames::IMAGE, 'img_id');
            $this->addForeignKey('oprecx_user_metas_user_id_fkey', TableNames::USER_META, 'user_id', TableNames::USER, 'id');
        }
        
        //$this->getDbConnection()->getSchema()->resetSequence(TableNames::USER, 100);
    }

    private function tableRec()
    {
        $this->dropTableIfExists(TableNames::RECRUITMENT);
        $this->dropTableIfExists(TableNames::REC_ADMIN);
        $this->dropTableIfExists(TableNames::RECRUITMENT_META);
        $this->dropTableIfExists(TableNames::REC_ELM);

        $this->createTable(TableNames::RECRUITMENT, array (
            'id'             => 'pk',
            'name'           => 'string NOT NULL UNIQUE',
            'full_name'      => 'string NOT NULL',
            'email'          => 'string NOT NULL',
            'created'        => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated'        => 'datetime DEFAULT NULL',
            'description'    => 'text NOT NULL',
            'type'           => 'string NOT NULL',
            'scope'          => 'string NOT NULL',
            'location'       => 'string NOT NULL',
            'link'           => 'string NOT NULL',
            'img_id'         => 'integer DEFAULT NULL',
            'reg_time_begin' => 'datetime NOT NULL',
            'reg_time_end'   => 'datetime NOT NULL',
            'div_min' => 'integer DEFAULT 1',
            'div_max'   => 'integer DEFAULT 2',
            'visibility'     => 'string NOT NULL DEFAULT \'public\'',
            //"FOREIGN KEY(img_id) REFERENCES {{images}} (img_id) ON DELETE SET NULL",
        ));

        $this->createTable(TableNames::REC_ADMIN, array (
            'rec_id'  => 'int NOT NULL',
            'user_id' => 'int NOT NULL',
            'rule'    => 'string NOT NULL',
            'last_access' => 'datetime DEFAULT NULL',
            
            "PRIMARY KEY (rec_id,user_id)",
            //"FOREIGN KEY(rec_id) REFERENCES {{recruitment}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
            //"FOREIGN KEY(user_id) REFERENCES {{users}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        $this->createTable(TableNames::RECRUITMENT_META, array (
            
            'rec_id'     => 'integer NOT NULL',
            'meta_name'  => 'string NOT NULL',
            'meta_value' => 'text NOT NULL',
            'created'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated'    => 'datetime NOT NULL',
            
            'PRIMARY KEY (rec_id, meta_name)',
            //"FOREIGN KEY(rec_id) REFERENCES {{recruitment}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        //$this->createIndex('oprecx_recruitment_metas_rec_id', TableNames::RECRUITMENT_META, 'rec_id');

        $this->createTable(TableNames::REC_ELM, array (
            'elm_id'  => 'pk',
            'rec_id'  => 'integer NOT NULL',
            'name'    => 'string NOT NULL',
            'weight'  => 'integer DEFAULT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',

            //"FOREIGN KEY(rec_id) REFERENCES {{recruitment}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        $this->createIndex('oprecx_rec_elm_rec_id', TableNames::REC_ELM, 'rec_id');

        if ($this->foreign) {
            $this->addForeignKey('oprecx_recruitment_img_id_fkey', TableNames::RECRUITMENT, 'img_id', TableNames::IMAGE, 'img_id');

            $this->addForeignKey('oprecx_recruitment_metas_rec_id_fkey', TableNames::RECRUITMENT_META, 'rec_id', TableNames::RECRUITMENT, 'id');

            $this->addForeignKey('oprecx_rec_admin_rec_id_fkey', TableNames::REC_ADMIN, 'rec_id', TableNames::RECRUITMENT, 'id');
            $this->addForeignKey('oprecx_rec_admin_user_id_fkey', TableNames::REC_ADMIN, 'user_id', TableNames::USER, 'id');

            $this->addForeignKey('oprecx_rec_elm_rec_id_fkey', TableNames::REC_ELM, 'rec_id', TableNames::RECRUITMENT, 'id');
        }
        
        // $this->getDbConnection()->getSchema()->resetSequence(TableNames::USER, 100);

    }

    private function tableDivision()
    {
        $this->dropTableIfExists(TableNames::DIVISION);
        $this->dropTableIfExists(TableNames::DIVISION_ELM);
        $this->dropTableIfExists(TableNames::DIVISION_CHOICE);

        $this->createTable(TableNames::DIVISION, array (
            'div_id'        => 'pk',
            'rec_id'        => 'integer NOT NULL',
            'name'          => 'string NOT NULL',
            'description'   => 'text DEFAULT NULL',
            'leader'        => 'string DEFAULT NULL',
            'max_applicant' => 'integer DEFAULT NULL',
            'max_staff'     => 'integer DEFAULT NULL',
            'min_staff'     => 'integer DEFAULT NULL',
            'enabled'       => 'integer NOT NULL DEFAULT 1',
            'created'       => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated'       => 'datetime DEFAULT NULL',
            'weight'        => 'integer NOT NULL DEFAULT 0',

            //"FOREIGN KEY(rec_id) REFERENCES {{recruitment}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        $this->createIndex('oprecx_divisions_rec_id', TableNames::DIVISION, 'rec_id');

        $this->createTable(TableNames::DIVISION_ELM, array (
            'div_id' => 'int',
            'elm_id' => 'int',

            "PRIMARY KEY (div_id,elm_id)",
            //"FOREIGN KEY(div_id) REFERENCES {{divisions}} (div_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
            //"FOREIGN KEY(elm_id) REFERENCES {{rec_elm}} (elm_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        $this->createTable(TableNames::DIVISION_CHOICE, array (
            'div_id'  => 'integer NOT NULL',
            'user_id' => 'integer NOT NULL',
            'choosed' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'weight'  => 'integer NOT NULL DEFAULT 0',

            "PRIMARY KEY (div_id,user_id)",
            //"FOREIGN KEY(div_id) REFERENCES {{divisions}} (div_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
            //"FOREIGN KEY(user_id) REFERENCES {{users}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        if ($this->foreign) {
            $this->addForeignKey('oprecx_divisions_rec_id_fkey', TableNames::DIVISION, 'rec_id', TableNames::RECRUITMENT, 'id');

            $this->addForeignKey('oprecx_division_elms_div_id_fkey', TableNames::DIVISION_ELM, 'div_id', TableNames::DIVISION, 'div_id');
            $this->addForeignKey('oprecx_division_elms_elm_id_fkey', TableNames::DIVISION_ELM, 'elm_id', TableNames::REC_ELM, 'elm_id');

            $this->addForeignKey('oprecx_division_choices_div_id_fkey', TableNames::DIVISION_CHOICE, 'div_id', TableNames::DIVISION, 'div_id');
            $this->addForeignKey('oprecx_division_choices_user_id_fkey', TableNames::DIVISION_CHOICE, 'user_id', TableNames::USER, 'id');
        }
    }

    private function tableForms()
    {
        $this->dropTableIfExists(TableNames::FORM);
        $this->dropTableIfExists(TableNames::FORM_FIELD);
        $this->dropTableIfExists(TableNames::FORM_VALUE);

        $this->createTable(TableNames::FORM, array (
            'elm_id' => 'INTEGER PRIMARY KEY',
            //"FOREIGN KEY(elm_id) REFERENCES {{rec_elm}} (elm_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        $this->createTable(TableNames::FORM_FIELD, array (
            'field_id' => 'pk',
            'form_id'  => 'integer NOT NULL',
            'name'     => 'string NOT NULL',
            'type'     => 'string NOT NULL',
            'desc'     => 'text NOT NULL',
            'weight'   => 'integer NOT NULL DEFAULT 0',
            'required' => 'integer NOT NULL DEFAULT 0',
            'options'  => 'text DEFAULT NULL',
            'created'  => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',

            //"FOREIGN KEY(form_id) REFERENCES {{forms}} (elm_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        $this->createIndex('oprecx_form_fields_form_id', TableNames::FORM_FIELD, 'form_id');

        $this->createTable(TableNames::FORM_VALUE, array (
            //'value_id' => 'pk',
            'field_id' => 'integer NOT NULL',
            'user_id'  => 'integer NOT NULL',
            'value'    => 'text DEFAULT NULL',
            'created'  => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated'  => 'datetime DEFAULT NULL',

            "PRIMARY KEY (field_id,user_id)",

            //"FOREIGN KEY(field_id) REFERENCES {{form_fields}} (field_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
            //"FOREIGN KEY(user_id) REFERENCES {{users}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        //$this->createIndex('oprecx_form_values_field_id', TableNames::FORM_VALUE, 'field_id');
        //$this->createIndex('oprecx_form_values_user_id', TableNames::FORM_VALUE, 'user_id');

        if ($this->foreign) {
            $this->addForeignKey('oprecx_forms_elm_id_fkey', TableNames::FORM, 'elm_id', TableNames::REC_ELM, 'elm_id');

            $this->addForeignKey('oprecx_form_fields_form_id_fkey', TableNames::FORM_FIELD, 'form_id', TableNames::FORM, 'elm_id');

            $this->addForeignKey('oprecx_form_values_field_id_fkey', TableNames::FORM_VALUE, 'field_id', TableNames::FORM_FIELD, 'field_id');
            $this->addForeignKey('oprecx_form_values_user_id_fkey', TableNames::FORM_VALUE, 'user_id', TableNames::USER, 'id');
        }
    }

    function dataDiv()
    {
        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 2,
            'rec_id'      => 1,
            'name'        => 'Biro 1',
            'description' => 'Penjelasan Biro',
        ));

        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 3,
            'rec_id'      => 3,
            'name'        => 'Departemen 1',
            'description' => 'Penjelasan Departemen',
        ));

        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 1,
            'rec_id'      => 1,
            'name'        => 'Departemen 1',
            'description' => 'Penjelasan Departemen',
        ));


        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 5,
            'rec_id'      => 4,
            'name'        => 'Departemen Pendidikan',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 6,
            'rec_id'      => 4,
            'name'        => 'Departemen Sosmas',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 7,
            'rec_id'      => 4,
            'name'        => 'Departemen Puskaban',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 8,
            'rec_id'      => 4,
            'name'        => 'Departemen Orsen',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 9,
            'rec_id'      => 4,
            'name'        => 'Biro Kominfo',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert(TableNames::DIVISION, array (
            'div_id'      => 10,
            'rec_id'      => 4,
            'name'        => 'Biro PSDM',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));



        $this->insert(TableNames::DIVISION_CHOICE, array (
            'div_id'  => 1,
            'user_id' => 6,
        ));

    }

    function dataForm()
    {
        $this->insert(TableNames::REC_ELM, array (
            'elm_id' => 1,
            'rec_id' => 1,
            'name'   => 'Informasi Pribadi',
        ));

        $this->insert(TableNames::DIVISION_ELM, array (
            'div_id' => 1,
            'elm_id' => 1,
        ));

        $this->insert(TableNames::DIVISION_ELM, array (
            'div_id' => 2,
            'elm_id' => 1,
        ));

        $this->insert(TableNames::FORM, array (
            'elm_id' => 1,
        ));

        $this->insert(TableNames::FORM_FIELD, array (
            'field_id' => 1,
            'form_id'  => 1,
            'name'     => 'Fakultas',
            'type'     => 'dropdownlist',
            'desc'     => '',
            'required' => 1,
            'options'  => serialize(array('items' => array('fk' => 'FK', 'fkg' => 'FKG', 'fmipa' => 'FMIPA', 'ft' => 'FT'))),
        ));

        $this->insert(TableNames::FORM_FIELD, array (
            'field_id' => 2,
            'form_id'  => 1,
            'name'     => 'Jurusan',
            'type'     => 'text',
            'desc'     => 'Jurusan kamu sekarang',
            'required' => 1,
            'options'  => serialize(array('maxlen' => 64)),
        ));

        $this->insert(TableNames::FORM_FIELD, array (
            'field_id' => 3,
            'form_id'  => 1,
            'name'     => 'Angkatan',
            'type'     => 'number',
            'desc'     => 'Tahun angkatan masuk',
            'weight'   => 0,
            'required' => 1,
            'options'  => '',
        ));

        $this->insert(TableNames::FORM_FIELD, array (
            'field_id' => 4,
            'form_id'  => 1,
            'name'     => 'Facebook',
            'type'     => 'text',
            'desc'     => 'Tahun angkatan masuk',
            'weight'   => 0,
            'required' => 0,
            'options'  => '',
        ));
    }

    private function dataRec()
    {
        $this->insert(TableNames::RECRUITMENT,
                array (
            'id'             => 1,
            'name'           => 'pemiraui2013',
            'full_name'      => 'Pemira IKM UI 2013',
            'email'          => "admin@pemira.ui.ac.id",
            'description'    => 'Pemilihan Raya Ikatan Keluarga Mahasiswa Universitas Indonesia 2013',
            'type'           => 'committee',
            'scope'          => 'university',
            'location'       => 'Universitas Indonesia',
            'link'           => "http://pemira.ui.ac.id/",
            'reg_time_begin' => "2013-03-10 00:00:00",
            'reg_time_end'   => "2013-04-19 00:00:00",
        ));

        $this->insert(TableNames::RECRUITMENT,
                array (
            'id'             => 3,
            'name'           => 'bemui2013',
            'full_name'      => 'BEM UI 2013',
            'email'          => "admin@bem.ui.ac.id",
            'description'    => 'Bada Eksekutif Mahasiswa Universitas Indonesia 2013',
            'type'           => 'rec',
            'scope'          => 'university',
            'location'       => 'Universitas Indonesia',
            'link'           => "http://pemira.ui.ac.id/",
            'reg_time_begin' => "2013-03-10 00:00:00",
            'reg_time_end'   => "2013-04-19 00:00:00",
        ));

        $this->insert(TableNames::RECRUITMENT,
                array (
            'id'             => 4,
            'name'           => 'forkomauibanten13',
            'full_name'      => 'Forkoma UI Banten 2013',
            'email'          => 'forkomabanten@gmail.com',
            'description'    => '<p>Forum Komununikasi Mahasiswa dan Alumni Universitas Indonesia asal Banten atau 
                sering disingkat dengan Forkoma UI Banten merupakan paguyuban daearah yang ...</p>',
            'type'           => 'rec',
            'scope'          => 'university',
            'location'       => 'Universitas Indonesia',
            'link'           => "http://forkomauibanten.com/",
            'reg_time_begin' => "2013-03-10 00:00:00",
            'reg_time_end'   => "2013-04-19 00:00:00",
        ));

        $this->insert(TableNames::REC_ADMIN, array (
            'rec_id'  => '1',
            'user_id' => '6',
            'rule'    => 'super',
        ));
        $this->insert(TableNames::REC_ADMIN, array (
            'rec_id'  => '4',
            'user_id' => '6',
            'rule'    => 'super',
        ));
    }

    function dataUser()
    {
        $this->insert(TableNames::USER, array (
            'id'        => 1,
            'email'     => 'admin@oprecx.com',
            'password'  => crypt('123'),
            'full_name' => 'Oprecx Admin',
        ));

        $this->insert(TableNames::USER, array (
            'id'        => 6,
            'email'     => 'frankenstein@ui.ac.id',
            'password'  => crypt('123'),
            'full_name' => 'Frankstein Bennington',
        ));

    }

    public function down()
    {
        //$this->execute('SET FOREIGN_KEY_CHECKS = 0');
        /*
        $this->execute('DROP TABLE IF EXISTS
            {{images}}, 
            {{users}}, {{user_metas}},
            {{recruitment}}, {{recruitment_metas}}, {{rec_admins}}, {{rec_elms}},
            {{divisions}}, {{division_elms}}, {{division_choices}},
            {{forms}}, {{form_fields}}, {{form_values}},
            {{interview_slots}}, {{interview_values}}
        ');
        */
        //$this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }

    public function dropTableIfExists($table)
    {
        $scheme = $this->getDbConnection()->getSchema();
        if (is_array($table)) {
            foreach ($table as $k => $v) {
                $table[$k] = $scheme->quoteTableName($v);
            }
            $table = implode (', ', $table);
        } 
        else $table = $scheme->quoteTableName($table);
        $sql = 'DROP TABLE IF EXISTS ' . $table;
        if ($this->driver == 'pgsql') $sql .= ' CASCADE';
        $this->execute($sql);
    }

    /*
    public function insert($table, $columns)
    {
        parent::insert($table, $columns);
        return $this->getDbConnection()->getLastInsertID();
    }

    */
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

