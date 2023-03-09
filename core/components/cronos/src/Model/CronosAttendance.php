<?php
namespace Cronos\Model;

use xPDO\xPDO;

/**
 * Class CronosAttendance
 *
 * @property integer $supervisor_id
 * @property integer $worker_id
 * @property string $event_date
 * @property string $event_type
 * @property string $lat
 * @property string $long
 * @property string $photo_check
 *
 * @package Cronos\Model
 */
class CronosAttendance extends \xPDO\Om\xPDOSimpleObject
{
}
