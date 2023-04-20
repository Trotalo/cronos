<?php
namespace Cronos\Model\mysql;

use xPDO\xPDO;

class CronosAttendance extends \Cronos\Model\CronosAttendance
{

    public static $metaMap = array (
        'package' => 'Cronos\\Model\\',
        'version' => '3.0',
        'table' => 'cronos_attendance',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'customer_id' => 0,
            'project_id' => NULL,
            'supervisor_id' => NULL,
            'worker_id' => NULL,
            'in_date' => 0,
            'out_date' => 0,
            'in_photo_check' => '',
            'out_photo_check' => '',
            'in_coordinates' => NULL,
            'out_coordinates' => NULL,
            'in_photo_location' => '',
            'out_photo_location' => '',
        ),
        'fieldMeta' => 
        array (
            'customer_id' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'default' => 0,
            ),
            'project_id' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
            ),
            'supervisor_id' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
            ),
            'worker_id' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
            ),
            'in_date' => 
            array (
                'dbtype' => 'int',
                'precision' => '20',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
            'out_date' => 
            array (
                'dbtype' => 'int',
                'precision' => '20',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
            'in_photo_check' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'out_photo_check' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'in_coordinates' => 
            array (
                'dbtype' => 'json',
                'phptype' => 'json',
                'null' => true,
            ),
            'out_coordinates' => 
            array (
                'dbtype' => 'json',
                'phptype' => 'json',
                'null' => true,
            ),
            'in_photo_location' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'out_photo_location' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
        ),
    );

}
