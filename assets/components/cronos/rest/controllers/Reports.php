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
use xPDO\Om\xPDOObject;

class CronosReports extends \MODX\Revolution\Rest\modRestController {
  /** @var string $classKey The xPDO class to use */
  public $classKey = 'none';
  /** @var string $defaultSortField The default field to sort by in the getList method */
  public $defaultSortField = 'id';
  /** @var string $defaultSortDirection The default direction to sort in the getList method */
  public $defaultSortDirection = 'ASC';

  public function __construct(modX $modx, modRestServiceRequest $request, array $config = array()) {
    parent::__construct($modx, $request, $config);
  }

  public function get() {
    $properties = $this->getProperties();

    /*$properties = array(
      'login_context' => 'web',
      'username'      => $properties['username'],
      'password'      => $properties['password'],
      'returnUrl'     => "/",
      'rememberme'    => 0,
    );

    $response = $this->modx->runProcessor('security/login', $properties);*/
    $this->generateCsv();
    return $this->success('Login executed');
  }

  public function outputCsv($assocDataArray) {
    if (!empty($assocDataArray)):

      $fp = fopen('php://output', 'w');
      fputcsv($fp, array_keys(reset($assocDataArray)));

      foreach ($assocDataArray as $values):
        fputcsv($fp, $values);
      endforeach;

      fclose($fp);
    endif;

    exit();
  }

  public function generateCsv() {
    $returnValue = array();

    $queryText = "select usrAttribute.internalKey as operator_id, 
              usrAttribute.fullname, attendance.id, attendance.supervisor_id, 
              attendance.worker_id, FROM_UNIXTIME(attendance.in_date) as entrada, 
              if(attendance.out_date = 0, 'No registra', FROM_UNIXTIME(attendance.out_date)) as salida, 
              attendance.in_photo_check, attendance.out_photo_check
              from modx_cronos_attendance attendance, 
                modx_user_attributes as usrAttribute
                    where attendance.worker_id = usrAttribute.internalKey";

    $query = $this->modx->query($queryText);

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      //$this->modx->log(xPDO::LOG_LEVEL_ERROR, json_encode($row));
      $returnValue[] = $row;
    }

    return $this->outputCsv($returnValue);
  }

}