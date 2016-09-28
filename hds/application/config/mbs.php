<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| List of controller for manage permission
|--------------------------------------------------------------------------
|
|
*/
$config['mbs_controllers']	= array(
    'employee',
    'company',
    'sub_company',
    'loan',
    'leave',
    'salary',
    'user',
    'permission'
);

/*
|--------------------------------------------------------------------------
| List of actions for permissioin
|--------------------------------------------------------------------------
|
*/
$config['mbs_operations'] = array(
    'add',
    'update',
    'delete',
    'list',
    'detail',
    'print',
    'download',
    'pdf'
);

/* End of file mbs.php */
/* Location: ./application/config/mbs.php */