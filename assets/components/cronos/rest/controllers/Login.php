<?php
/**
 * Login
 * Copyright 2010 by Jason Coward <jason@modx.com> and Shaun McCormick
 * <shaun@modx.com>
 * Login is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 * Login is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with
 * Login; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 * @package login
 */
/**
 * MODx Login Snippet
 * This snippet handles login POSTs, sending the user back to where they came from or to a specific
 * location if specified in the POST.
 * @package login
 * @property textfield actionKey The REQUEST variable containing the action to take.
 * @property textfield loginKey The actionKey for login.
 * @property textfield logoutKey The actionKey for logout.
 * @property boolean loginViaEmail Enable login via username or email address (either one!) [default: false]
 * @property list tplType The type of template to expect for the views:
 *  modChunk - name of chunk to use
 *  file - full path to file to use as tpl
 *  embedded - the tpl is embedded in the page content
 *  inline - the tpl is inline content provided directly
 * @property textfield loginTpl The template for the login view (content based on tplType)
 * @property textfield logoutTpl The template for the logout view (content based on tplType)
 * @property textfield errTpl The template for any errors that occur when processing an view
 * @property list errTplType The type of template to expect for the error messages:
 *  modChunk - name of chunk to use
 *  file - full path to file to use as tpl
 *  inline - the tpl is inline content provided directly
 * @property integer logoutResourceId An explicit resource id to redirect users to on logout
 * @property string loginMsg The string to use for the login action. Defaults to
 * the lexicon string "login".
 * @property string logoutMsg The string to use for the logout action. Defaults
 * to the lexicon string "login.logout"
 */
use MODX\Revolution\Rest\modRestController;
use MODX\Revolution\Rest\modRestServiceRequest;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modResource;


class CronosLogin extends  \MODX\Revolution\Rest\modRestController {
  /** @var string $classKey The xPDO class to use */
  public $classKey = 'none';
  /** @var string $defaultSortField The default field to sort by in the getList method */
  public $defaultSortField = 'id';
  /** @var string $defaultSortDirection The default direction to sort in the getList method */
  public $defaultSortDirection = 'ASC';

  public function __construct(modX $modx,modRestServiceRequest $request,array $config = array()){
    parent::__construct($modx, $request, $config);
  }

  public function post() {
    $properties = $this->getProperties();

    $properties = array(
      'login_context' => 'web',
      'username'      => $properties['username'],
      'password'      => $properties['password'],
      'returnUrl'     => "/",
      'rememberme'    => 0,
    );

    $rawResponse = $this->modx->runProcessor('security/login', $properties);
    $response = $rawResponse->getResponse();
    if (!$rawResponse->response["success"]) {
      return $this->failure($rawResponse->response['message'], $response, 401);
    } else {
      //Retrieve the user
      $user = $this->modx->getObject(modUser::class, array('username' => $properties['username']));
      //we retrieve the id for the exisitng respurces
      //TODO deletemete
      $resources = array();
      $result = $this->modx->query("SELECT * 
                                            FROM modx_site_content 
                                            WHERE pagetitle='serviceManager'
                                            OR pagetitle='supervisor'");
      if (!is_object($result)) {
        return $this->failure('Missing configuration, please contact your system admin',
          null, 500);
      }
      else {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          //$this->modx->log(xPDO::LOG_LEVEL_ERROR, json_encode($row));
          $resources[$row['pagetitle']] = $row;
        }
      }
      //TODO close deeleteme
      $resourceId =  $resources['serviceManager']['id'];
      $serviceManager = $this->modx->makeUrl($resourceId);
      $resourceId = $resources['supervisor']['id'];
      $supervisorPage = $this->modx->makeUrl($resourceId);
      $primaryGroup = $user->get('primary_group');
      //we get the exiting groups
      $serviceMgrGroup = $this->modx->getObject(modUserGroup::class, ['name'=> 'ServiceManager']);
      $operatorMgrGroup = $this->modx->getObject(modUserGroup::class, ['name'=> 'Supervisor']);
      if ($primaryGroup === $serviceMgrGroup->get('id')  || $primaryGroup ==1 ) {
        $response['redirect'] = $serviceManager;
      }
      elseif ($primaryGroup === $operatorMgrGroup->get('id')) {
        $response['redirect'] = $supervisorPage;
      }
    }


    return $this->success('Login executed',$response);
  }

  public function delete()
  {
    $response = $this->modx->runProcessor('security/logout');
    return $this->success('Logout executed',$response);
  }

}