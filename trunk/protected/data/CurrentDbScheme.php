<?php

class CurrentDbScheme extends CDbMigration
{
    private $foreign, $driver;

    public function up()
    {
        $this->foreign = false;

        $tmp = explode(':', $this->getDbConnection()->connectionString, 2);
        $this->driver = strtolower($tmp[0]);

        if ($this->driver == 'mysql')
            $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        if ($this->driver == 'sqlite')
            $this->foreign = false;

        $this->dropTableIfExists('{{images}}');
        $this->createTable('{{images}}', array (
            'img_id'    => 'pk',
            'file_path' => 'string NOT NULL',
            'file_url'  => 'string NOT NULL',
            'width'     => 'integer NOT NULL',
            'height'    => 'integer NOT NULL',
            'created'   => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));

        $this->tableUser();
        $this->dataUser();

        $this->tableOrg();
        $this->dataOrg();

        $this->tableDivision();
        $this->dataDiv();

        $this->tableForms();
        $this->dataForm();

        $this->tableWawancara();
        $this->dataWawancara();


    }

    private function tableWawancara() {
        $this->dropTableIfExists('{{interview_slots}}');
        $this->dropTableIfExists('{{interview_user_slots}}');

        $this->createTable('{{interview_slots}}', array(
            'elm_id' => 'pk',
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

        $this->createTable('{{interview_user_slots}}', array(
            'slot_id' => 'integer NOT NULL',
            'user_id' => 'integer NOT NULL',
            'time' => 'datetime NOT NULL',
            'created' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',

            'PRIMARY KEY (slot_id, user_id, time)',
        ));

        if ($this->foreign) {
            $this->addForeignKey('oprecx_interview_slots_elm_id_fkey', '{{interview_slots}}', 'elm_id', '{{org_elms}}', 'elm_id');
            $this->addForeignKey('oprecx_interview_user_slots_slot_id_fkey', '{{interview_user_slots}}', 'slot_id', '{{interview_slots}}', 'elm_id');
            $this->addForeignKey('oprecx_interview_user_slots_user_id_fkey', '{{interview_user_slots}}', 'user_id', '{{users}}', 'id');
        }
    }

    private function dataWawancara() {
        $this->insert('{{org_elms}}', array (
            'elm_id' => 2,
            'org_id' => 1,
            'name' => 'Default Slot',
        ));

        $this->insert('{{interview_slots}}', array(
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

        $this->insert('{{division_elms}}', array (
            'div_id' => 1,
            'elm_id' => 2,
        ));

        $this->insert('{{division_elms}}', array (
            'div_id' => 2,
            'elm_id' => 2,
        ));

    }

    public function getLastInsertID($seqname = '') {
        return $this->getDbConnection()->getLastInsertID($seqname);
    }

    private function tableUser()
    {
        $this->dropTableIfExists(TableNames::USERS);
        $this->dropTableIfExists('{{user_metas}}');

        $this->createTable('{{users}}', array (
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
        //$this->createIndex('oprecx_users_users_img_id', '{{users}}', 'img_id');

        $this->createTable('{{user_metas}}', array (
            'user_id'    => 'integer NOT NULL',
            'meta_name'  => 'string NOT NULL',
            'meta_value' => 'text NOT NULL',
            'created'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated'    => 'datetime DEFAULT NULL',

            'PRIMARY KEY (user_id, meta_name)',
            //"FOREIGN KEY(user_id) REFERENCES {{users}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));     
        //$this->createIndex('oprecx_user_metas_user_id', '{{user_metas}}', 'user_id');

        if ($this->foreign) {
            $this->addForeignKey('oprecx_users_img_id_fkey', '{{users}}', 'img_id', '{{images}}', 'img_id');
            $this->addForeignKey('oprecx_user_metas_user_id_fkey', '{{user_metas}}', 'user_id', '{{users}}', 'id');
        }
    }

    private function tableOrg()
    {
        $this->dropTableIfExists('{{organizations}}');
        $this->dropTableIfExists('{{org_admins}}');
        $this->dropTableIfExists('{{organization_metas}}');
        $this->dropTableIfExists('{{org_elms}}');

        $this->createTable('{{organizations}}', array (
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
            'visibility'     => 'string NOT NULL DEFAULT \'public\'',
            //"FOREIGN KEY(img_id) REFERENCES {{images}} (img_id) ON DELETE SET NULL",
        ));

        $this->createTable('{{org_admins}}', array (
            'org_id'  => 'int NOT NULL',
            'user_id' => 'int NOT NULL',
            'rule'    => 'string NOT NULL',
            'last_access' => 'datetime DEFAULT NULL',
            
            "PRIMARY KEY (org_id,user_id)",
            //"FOREIGN KEY(org_id) REFERENCES {{organizations}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
            //"FOREIGN KEY(user_id) REFERENCES {{users}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        $this->createTable('{{organization_metas}}', array (
            
            'org_id'     => 'integer NOT NULL',
            'meta_name'  => 'string NOT NULL',
            'meta_value' => 'text NOT NULL',
            'created'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated'    => 'datetime NOT NULL',
            
            'PRIMARY KEY (org_id, meta_name)',
            //"FOREIGN KEY(org_id) REFERENCES {{organizations}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        //$this->createIndex('oprecx_organization_metas_org_id', '{{organization_metas}}', 'org_id');

        $this->createTable('{{org_elms}}', array (
            'elm_id'  => 'pk',
            'org_id'  => 'integer NOT NULL',
            'name'    => 'string NOT NULL',
            'weight'  => 'integer DEFAULT NULL',
            'created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated' => 'datetime DEFAULT NULL',

            //"FOREIGN KEY(org_id) REFERENCES {{organizations}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        $this->createIndex('oprecx_org_elms_org_id', '{{org_elms}}', 'org_id');

        if ($this->foreign) {
            $this->addForeignKey('oprecx_organizations_img_id_fkey', '{{organizations}}', 'img_id', '{{images}}', 'img_id');

            $this->addForeignKey('oprecx_organization_metas_org_id_fkey', '{{organization_metas}}', 'org_id', '{{organizations}}', 'id');

            $this->addForeignKey('oprecx_org_admins_org_id_fkey', '{{org_admins}}', 'org_id', '{{organizations}}', 'id');
            $this->addForeignKey('oprecx_org_admins_user_id_fkey', '{{org_admins}}', 'user_id', '{{users}}', 'id');

            $this->addForeignKey('oprecx_org_elms_org_id_fkey', '{{org_elms}}', 'org_id', '{{organizations}}', 'id');
        }

    }

    private function tableDivision()
    {
        $this->dropTableIfExists('{{divisions}}');
        $this->dropTableIfExists('{{division_elms}}');
        $this->dropTableIfExists('{{division_choices}}');

        $this->createTable('{{divisions}}', array (
            'div_id'        => 'pk',
            'org_id'        => 'integer NOT NULL',
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

            //"FOREIGN KEY(org_id) REFERENCES {{organizations}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));
        $this->createIndex('oprecx_divisions_org_id', '{{divisions}}', 'org_id');

        $this->createTable('{{division_elms}}', array (
            'div_id' => 'int',
            'elm_id' => 'int',

            "PRIMARY KEY (div_id,elm_id)",
            //"FOREIGN KEY(div_id) REFERENCES {{divisions}} (div_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
            //"FOREIGN KEY(elm_id) REFERENCES {{org_elms}} (elm_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        $this->createTable('{{division_choices}}', array (
            'div_id'  => 'integer NOT NULL',
            'user_id' => 'integer NOT NULL',
            'choosed' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'weight'  => 'integer NOT NULL DEFAULT 0',

            "PRIMARY KEY (div_id,user_id)",
            //"FOREIGN KEY(div_id) REFERENCES {{divisions}} (div_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
            //"FOREIGN KEY(user_id) REFERENCES {{users}} (id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        if ($this->foreign) {
            $this->addForeignKey('oprecx_divisions_org_id_fkey', '{{divisions}}', 'org_id', '{{organizations}}', 'id');

            $this->addForeignKey('oprecx_division_elms_div_id_fkey', '{{division_elms}}', 'div_id', '{{divisions}}', 'div_id');
            $this->addForeignKey('oprecx_division_elms_elm_id_fkey', '{{division_elms}}', 'elm_id', '{{org_elms}}', 'elm_id');

            $this->addForeignKey('oprecx_division_choices_div_id_fkey', '{{division_choices}}', 'div_id', '{{divisions}}', 'div_id');
            $this->addForeignKey('oprecx_division_choices_user_id_fkey', '{{division_choices}}', 'user_id', '{{users}}', 'id');
        }
    }

    private function tableForms()
    {
        $this->dropTableIfExists('{{forms}}');
        $this->dropTableIfExists('{{form_fields}}');
        $this->dropTableIfExists('{{form_values}}');

        $this->createTable('{{forms}}', array (
            'elm_id' => 'INTEGER PRIMARY KEY',
            //"FOREIGN KEY(elm_id) REFERENCES {{org_elms}} (elm_id) ON UPDATE NO ACTION ON DELETE NO ACTION",
        ));

        $this->createTable('{{form_fields}}', array (
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
        $this->createIndex('oprecx_form_fields_form_id', '{{form_fields}}', 'form_id');

        $this->createTable('{{form_values}}', array (
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
        //$this->createIndex('oprecx_form_values_field_id', '{{form_values}}', 'field_id');
        //$this->createIndex('oprecx_form_values_user_id', '{{form_values}}', 'user_id');

        if ($this->foreign) {
            $this->addForeignKey('oprecx_forms_elm_id_fkey', '{{forms}}', 'elm_id', '{{org_elms}}', 'elm_id');

            $this->addForeignKey('oprecx_form_fields_form_id_fkey', '{{form_fields}}', 'form_id', '{{forms}}', 'elm_id');

            $this->addForeignKey('oprecx_form_values_field_id_fkey', '{{form_values}}', 'field_id', '{{form_fields}}', 'field_id');
            $this->addForeignKey('oprecx_form_values_user_id_fkey', '{{form_values}}', 'user_id', '{{users}}', 'id');
        }
    }

    function dataDiv()
    {
        $this->insert('{{divisions}}', array (
            'div_id'      => 2,
            'org_id'      => 1,
            'name'        => 'Biro 1',
            'description' => 'Penjelasan Biro',
        ));

        $this->insert('{{divisions}}', array (
            'div_id'      => 3,
            'org_id'      => 3,
            'name'        => 'Departemen 1',
            'description' => 'Penjelasan Departemen',
        ));

        $this->insert('{{divisions}}', array (
            'div_id'      => 1,
            'org_id'      => 1,
            'name'        => 'Departemen 1',
            'description' => 'Penjelasan Departemen',
        ));


        $this->insert('{{divisions}}', array (
            'div_id'      => 5,
            'org_id'      => 4,
            'name'        => 'Departemen Pendidikan',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert('{{divisions}}', array (
            'div_id'      => 6,
            'org_id'      => 4,
            'name'        => 'Departemen Sosmas',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert('{{divisions}}', array (
            'div_id'      => 7,
            'org_id'      => 4,
            'name'        => 'Departemen Puskaban',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert('{{divisions}}', array (
            'div_id'      => 8,
            'org_id'      => 4,
            'name'        => 'Departemen Orsen',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert('{{divisions}}', array (
            'div_id'      => 9,
            'org_id'      => 4,
            'name'        => 'Biro Kominfo',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));

        $this->insert('{{divisions}}', array (
            'div_id'      => 10,
            'org_id'      => 4,
            'name'        => 'Biro PSDM',
            'description' => '<p>Merupakan departemen yang bla-bla-bla</p>',
        ));



        $this->insert('{{division_choices}}', array (
            'div_id'  => 1,
            'user_id' => 6,
        ));

    }

    function dataForm()
    {
        $this->insert('{{org_elms}}', array (
            'elm_id' => 1,
            'org_id' => 1,
            'name'   => 'Informasi Pribadi',
        ));

        $this->insert('{{division_elms}}', array (
            'div_id' => 1,
            'elm_id' => 1,
        ));

        $this->insert('{{division_elms}}', array (
            'div_id' => 2,
            'elm_id' => 1,
        ));

        $this->insert('{{forms}}', array (
            'elm_id' => 1,
        ));

        $this->insert('{{form_fields}}', array (
            'field_id' => 1,
            'form_id'  => 1,
            'name'     => 'Fakultas',
            'type'     => 'dropdownlist',
            'desc'     => '',
            'required' => 1,
            'options'  => serialize(array('items' => array('fk' => 'FK', 'fkg' => 'FKG', 'fmipa' => 'FMIPA', 'ft' => 'FT'))),
        ));

        $this->insert('{{form_fields}}', array (
            'field_id' => 2,
            'form_id'  => 1,
            'name'     => 'Jurusan',
            'type'     => 'text',
            'desc'     => 'Jurusan kamu sekarang',
            'required' => 1,
            'options'  => serialize(array('maxlen' => 64)),
        ));

        $this->insert('{{form_fields}}', array (
            'field_id' => 3,
            'form_id'  => 1,
            'name'     => 'Angkatan',
            'type'     => 'number',
            'desc'     => 'Tahun angkatan masuk',
            'weight'   => 0,
            'required' => 1,
            'options'  => '',
        ));

        $this->insert('{{form_fields}}', array (
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

    private function dataOrg()
    {
        $this->insert('{{organizations}}',
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

        $this->insert('{{organizations}}',
                array (
            'id'             => 3,
            'name'           => 'bemui2013',
            'full_name'      => 'BEM UI 2013',
            'email'          => "admin@bem.ui.ac.id",
            'description'    => 'Bada Eksekutif Mahasiswa Universitas Indonesia 2013',
            'type'           => 'org',
            'scope'          => 'university',
            'location'       => 'Universitas Indonesia',
            'link'           => "http://pemira.ui.ac.id/",
            'reg_time_begin' => "2013-03-10 00:00:00",
            'reg_time_end'   => "2013-04-19 00:00:00",
        ));

        $this->insert('{{organizations}}',
                array (
            'id'             => 4,
            'name'           => 'forkomauibanten13',
            'full_name'      => 'Forkoma UI Banten 2013',
            'email'          => 'forkomabanten@gmail.com',
            'description'    => '<p>Forum Komununikasi Mahasiswa dan Alumni Universitas Indonesia asal Banten atau 
                sering disingkat dengan Forkoma UI Banten merupakan paguyuban daearah yang ...</p>',
            'type'           => 'org',
            'scope'          => 'university',
            'location'       => 'Universitas Indonesia',
            'link'           => "http://forkomauibanten.com/",
            'reg_time_begin' => "2013-03-10 00:00:00",
            'reg_time_end'   => "2013-04-19 00:00:00",
        ));

        $this->insert('{{org_admins}}', array (
            'org_id'  => '1',
            'user_id' => '6',
            'rule'    => 'super',
        ));
        $this->insert('{{org_admins}}', array (
            'org_id'  => '4',
            'user_id' => '6',
            'rule'    => 'super',
        ));
    }

    function dataUser()
    {
        $this->insert('{{users}}', array (
            'id'        => 1,
            'email'     => 'admin@oprecx.com',
            'password'  => crypt('123'),
            'full_name' => 'Oprecx Admin',
        ));

        $this->insert('{{users}}', array (
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
            {{organizations}}, {{organization_metas}}, {{org_admins}}, {{org_elms}},
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

