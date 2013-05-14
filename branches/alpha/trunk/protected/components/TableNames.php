<?php

/**
 * Class ini berisi nama table yang dipakai dalam sql.
 * penggunaan kelas ini dimaksudkan agar proses refactoring menjadi lebih mudah.
 *
 * @author abie
 */
abstract class TableNames
{
    // USER
    const USER = 'user';
    public static function USER_as($alias) {
        return self::USER . ' ' . $alias;
    }
    
    const USER_META = 'user_meta';
    public static function USER_META_as($alias) {
        return self::USER_META . ' ' . $alias;
    }
    
    // RECRUITMENT
    const RECRUITMENT = 'recruitment';
    public static function RECRUITMENT_as($alias) {
        return self::RECRUITMENT . ' ' . $alias;
    }
    
    const RECRUITMENT_META = 'recruitment_meta';
    public static function RECRUITMENT_META_as($alias) {
        return self::RECRUITMENT_META . ' ' . $alias;
    }
    
    const REC_ADMIN = 'rec_admin';
    public static function REC_ADMIN_as($alias) {
        return self::REC_ADMIN . ' ' . $alias;
    }
    
    const REC_ELM = 'rec_elm';
    public static function REC_ELM_as($alias) {
        return self::REC_ELM . ' ' . $alias;
    }
    
    const DIVISION = 'division';
    const DIVISION_CHOICE = 'division_choice';
    const DIVISION_ELM = 'division_elm';
    
    const FORM = 'form';
    const FORM_FIELD = 'form_field';
    const FORM_VALUE = 'form_value';
    
    const INTERVIEW_SLOT = 'interview_slot';
    const INTERVIEW_USER_SLOT = 'interview_user_slot';
    
    const IMAGE = 'image';
    
    
    
    
    
    
}

?>
