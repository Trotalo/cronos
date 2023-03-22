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
    $userId = $this->modx->user->get('id');
    $logedUserGroupQuery = "select modGroup.*, users.id as userId
                              from modx.modx_membergroup_names modGroup, 
                                modx.modx_users users, modx.modx_user_attributes usrAttr
                                where usrAttr.internalKey = $userId
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
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $returnValue[] = $row;
    }

    return $this->collection($returnValue);
  }

  public function post()
  {
    $userId = $userId = $this->modx->user->get('id');
    $this->properties['supervisor_id'] = $userId;
    if ($this->properties['out_date'] === 0) {
      $this->properties['in_date'] = date('Y-m-d H:i:s');
    } else {
      $this->properties['out_date'] = date('Y-m-d H:i:s');
    }
    parent::post();
  }

}