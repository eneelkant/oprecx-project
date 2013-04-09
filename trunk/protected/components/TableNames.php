<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TableNames
 *
 * @author abie
 */
abstract class TableNames
{
    const USERS = '{{users}}';
    const USER_METAS = '{{user_metas}}';
    
    const ORGANIZATIONS = '{{organizations}}';
    const ORGANIZATION_METAS = '{{organization_metas}}';
    const ORG_ADMINS = 'org_admins';
    const ORG_ELMS = 'org_elms';
    
    const DIVISIONS = 'divisions';
    const DIVISION_CHOICES = 'division_choices';
    const DIVISION_ELMS = 'division_elms';
    
    const FORMS = 'forms';
    const FORM_FIELDS = 'form_fields';
    const FORM_VALUES = 'form_values';
    
    const INTERVIEW_SLOTS = 'interview_slots';
    const INTERVIEW_USER_SLOTS = 'interview_user_slots';
}

?>
