<?php
namespace Cronos\Model\mysql;

use xPDO\xPDO;

class CronosProjects extends \Cronos\Model\CronosProjects
{

    public static $metaMap = array (
        'package' => 'Cronos\\Model\\',
        'version' => '3.0',
        'table' => 'cronos_projects',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'customer_id' => 0,
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
            'customer_id' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'default' => 0,
            ),
        ),
    );

}
