<?php
namespace Cronos\Model\mysql;

use xPDO\xPDO;

class CronosAttendance extends \Cronos\Model\CronosAttendance
{

    public static $metaMap = array (
        'package' => 'Cronos\\Model\\',
        'version' => '3.0',
        'table' => 'vlox_cronos_attendance',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'supervisor_id' => NULL,
            'worker_id' => NULL,
            'in_date' => 0,
            'out_date' => 0,
            'in_photo_check' => '',
            'out_photo_check' => '',
        ),
        'fieldMeta' => 
        array (
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
        ),
    );

}
