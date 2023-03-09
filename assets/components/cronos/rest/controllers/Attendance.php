<?php

use MODX\Revolution\Rest\modRestController;
use MODX\Revolution\Rest\modRestServiceRequest;
use xPDO\Om\xPDOObject;

class CronosAttendance extends  modRestController {

  /** @var string $classPrefix */
  public $vloxPrefix;

  /** @var string $classPrefix */
  public $modxPrefix;

  /** @var string $classKey The xPDO class to use */
  public $classKey = 'Cronos\Model\CronosAttendance';

  /** @var string $classAlias The alias of the class when used in the getList method */
  public $classAlias  = 'CronosAttendance';

  /** @var string $defaultSortField The default field to sort by in the getList method */
  public $defaultSortField = 'id';
  /** @var string $defaultSortDirection The default direction to sort in the getList method */
  public $defaultSortDirection = 'ASC';

  public $primaryKeyField = 'id';

  public function __construct(modX $modx,modRestServiceRequest $request,array $config = array()){
    parent::__construct($modx, $request, $config);
    $isMODX3 = $modx->getVersionData()['version'] >= 3;
    $this->vloxPrefix = $isMODX3 ? 'Vlox\Model\\' : '';
    $this->modxPrefix = $isMODX3 ? 'MODX\Revolution\\' : '';
  }

  /**
   * Handle user creation
   *
   * @abstract
   */


  public function get() {
    $pk = $this->getProperty($this->primaryKeyField);
    //we query the user and check the type, if service manager return everithing else return only needed data
    $sessionId = $this->modx->quote($_COOKIE['PHPSESSID']);
    $logedUserGroupQuery = "select modGroup.*, users.id as userId
                              from modx.modx_membergroup_names modGroup, 
                                modx.modx_users users, modx.modx_user_attributes usrAttr
                                where usrAttr.sessionid = $sessionId
                                and usrAttr.internalKey = users.id
                                and users.primary_group = modGroup.id";

    $userGroupQ = $this->modx->query($logedUserGroupQuery);

    if (!is_object($userGroupQ)) {
      return $this->failure("No existing session for the user!", null, 400);
    }

    $userGroup = $userGroupQ->fetch(PDO::FETCH_ASSOC);
    if (!$userGroup) {
      return $this->failure("No existing session for the user!", null, 400);
    }
    $queryText = '';
    if (strcmp($userGroup['name'], 'ServiceManager') === 0 ) {
      $queryText = "select usrAttribute.internalKey as operator_id, 
                    usrAttribute.fullname, attendance.*
                    from modx_vlox_cronos_attendance attendance, 
                      modx_user_attributes as usrAttribute
                          where attendance.worker_id = usrAttribute.internalKey";
    } else {
      $userId = $userGroup['userId'];
      $queryText = "select attendance.*, usrAttribute.internalKey as operator_id, 
                    usrAttribute.fullname
                    from modx_vlox_cronos_attendance attendance, 
                      modx_user_attributes as usrAttribute
                          where attendance.supervisor_id =  $userId
                          and attendance.out_date = 0
                          and attendance.worker_id = usrAttribute.internalKey";
    }

    $query = $this->modx->query($queryText);

    if (is_null($query)) {
      return $this->collection([], 0);
    }

    $returnValue = array();
    while ($row = $query->fetch()) {
      //$this->modx->log(xPDO::LOG_LEVEL_ERROR, json_encode($row));
      $dataRow = new stdClass;
      $dataRow->id = $row['id'];
      $dataRow->worker_id = $row['worker_id'];
      $dataRow->supervisor_id = $row['supervisor_id'];
      $dataRow->in_date = $row['in_date'];
      $dataRow->out_date = $row['out_date'];
      $dataRow->in_photo_check = $row['in_photo_check'];
      $dataRow->out_photo_check = $row['out_photo_check'];
      $dataRow->fullname = $row['fullname'];
      $returnValue[] = $dataRow;
    }
    /*
     * id, operator_id, fullname, event_date, photo_check, comments
     * */
    return $this->collection($returnValue);
  }

  public function post()
  {
    $sessionId = $this->modx->quote($_COOKIE['PHPSESSID']);
    $userProfile = $this->modx->getObject($this->modxPrefix . 'modUserProfile',
      ['sessionid' => $_COOKIE['PHPSESSID']]);
    if (empty($userProfile)) {
      return $this->failure('No existing session for the user!', null, 400);
    }
    $this->properties['supervisor_id'] = $userProfile->get('internalKey');
    parent::post();
  }

}