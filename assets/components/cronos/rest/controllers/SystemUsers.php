<?php

use MODX\Revolution\Rest\modRestController;
use MODX\Revolution\Rest\modRestServiceRequest;
use xPDO\Om\xPDOObject;

class CronosSystemUsers  extends  modRestController {

  /** @var string $classPrefix */
  public $vloxPrefix;

  /** @var string $classPrefix */
  public $modxPrefix;

  /** @var string $classKey The xPDO class to use */
  public $classKey = 'MODX\Revolution\modUser';

  /** @var string $classAlias The alias of the class when used in the getList method */
  public $classAlias  = 'modUser';

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
    $this->modx->lexicon->load('Cronos:default');
  }

  /**
   * Handle user creation
   *
   * @abstract
   */
  public function post(){
    $properties = $this->getProperties();
    $email = $properties['email'];
    $user = $this->modx->getObject($this->modxPrefix . 'modUser', [
      'username' => $email
    ]);
// Check that we have a User object
    if (!is_object($user)) {
      try {
        // No User object, we can safely assume that there is
        // no user registered with this Username
        // so we can go ahead and create one
        $user = $this->modx->newObject($this->modxPrefix . 'modUser');
        // Generate a password for this new user
        $pwd = $properties['id']; //$user->generatePassword();
        //GEt the user group id based on the selected item
        $internalGroup = $this->modx->getObject(
          $this->modxPrefix . 'modUserGroup', ['name'=> $properties['userType']]);

        if (empty($internalGroup)) {
          return $this->failure($this->modx->lexicon('cronos.groups.missing'), null, 500);
        }
        // Set some details
        $user->fromArray([
          'username' => $email,
          'password' => $pwd,
          'active' => $properties['userType'] === 'Supervisor' ? 1 : 0, // Important! Set them to active
          'primary_group' => $internalGroup->get('id')
        ]);

        $userProfile = $this->modx->newObject($this->modxPrefix . 'modUserProfile');
        $userProfile->fromArray([
          'internalKey' => $user->get('id'),
          'blockeduntil' => 0,
          'blocked' => $properties['userType'] === 'Supervisor' ? 0 : 1,
          'blockedafter' => 0,
          'fullname'  => $properties['name'],
          'email'     => $properties['email'],
          'mobilephone' => $properties['phone'],
          'comment' => $properties['id'],
          'extended'  => [
            'first_login' => 1
          ]
        ]);
        $user->addOne($userProfile);

        if ($user->save() === false) {
          return $this->failure($this->modx->lexicon('cronos.users.failed'), null, 500);
        }
        // Add this user to the right user group
        // Params -> groupID, roleID, rank
        // check out https://docs.modx.com/current/en/building-sites/client-proofing/security/user-groups
        $user->joinGroup($internalGroup->get('id'));
        $objectArray = $user->toArray();
        return $this->success('', $objectArray);
      } catch (RuntimeException $e) {
        return $this->failure($e->getMessage(), null, 500);
      }
    } else {
      return $this->failure($this->modx->lexicon('cronos.users.exists'), ['email' => $email], 409);
    }
  }

  public function get() {
    $query = $this->modx->query(" 
      select modUser.id, usrAttribute.fullname, modUser.primary_group, modGroups.name, usrAttribute.email
        from modx_users as modUser, modx_user_attributes as usrAttribute,
        modx.modx_membergroup_names modGroups
          where usrAttribute.internalKey = modUser.id
            and modUser.primary_group = modGroups.id
            and (modGroups.name = 'Supervisor' or modGroups.name = 'Operator')
      ");

    if (is_null($query)) {
      return $this->collection([], 0);
    }

    $returnValue = array();
    while ($row = $query->fetch()) {
      //$this->modx->log(xPDO::LOG_LEVEL_ERROR, json_encode($row));
      $dataRow = new stdClass;
      $dataRow->id = $row['id'];
      $dataRow->fullname = $row['fullname'];
      $dataRow->group = $row['primary_group'];
      $dataRow->email = $row['email'];
      $returnValue[] = $dataRow;
    }
    return $this->collection($returnValue);
  }

}