<?php
namespace Cronos\Model\mysql;

use xPDO\xPDO;

class CronosCustomers extends \Cronos\Model\CronosCustomers
{

    public static $metaMap = array (
        'package' => 'Cronos\\Model\\',
        'version' => '3.0',
        'table' => 'cronos_customers',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
        ),
        'fieldMeta' => 
        array (
            'name' => 
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
