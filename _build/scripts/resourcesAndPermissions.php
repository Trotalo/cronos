<?php

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modX;
use xPDO\Transport\xPDOTransport;

if ($options[xPDOTransport::PACKAGE_ACTION] === xPDOTransport::ACTION_UNINSTALL) {
  return true;
}

$modx =& $transport->xpdo;

/**
 * @param $parent id of the parent resource
 * @param $name the name of the resource
 * @param $contents the resource contents
 * @param $resGroup the group that the respurce needs permissions
 * @param $template the template that needs to be applied
 * @param $modx
 */
function createResource($parent, $name, $contents, $resGroup, $modx){

  //first we check if the resource exists
  $resource = $modx->getObject('modResource', ['pagetitle' => $name]);

  $resource_data = array(
    'pagetitle' => $name, // The title of the new resource
    'parent' => $parent, // Assign the new resource to the parent
    //'template' => $template, // The ID of the template to use
    'content' => $contents,
  );

  if (empty($resource)) {
    $resource = $modx->newObject('modResource');
  }

  // Create the new resource
  $resource->fromArray($resource_data);
  $resource->save();
  if(!empty($resGroup)) {
    $resource->set('resource_groups', array($resGroup));
    $resource->save();
  }
  return $resource->get('id');
}

/** @var modX $modx */


$acls = ['Operator', 'ServiceManager', 'Supervisor'];

foreach ($acls as $acl) {
  $properties = array(
    'parent' => '0',
    'name'      => $acl,
    'description'      => $acl,
    'aw_users'     => '',
    'aw_resource_groups'    => '',
    'aw_parallel'    => '1',
    'aw_contexts'    => 'web',
    'policy'    => '',
    'aw_categories'    => '',
  );
  $rawResponse = $modx->runProcessor('security/group/create', $properties);
}

//once the acls has been created, create the resources

//First we crate the login:
$content = <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite App</title>
    <script type="module" crossorigin src="/assets/components/cronos/1/js/index-4b24ec74.js"></script>
    <link rel="stylesheet" href="/assets/components/cronos/1/css/index-a43169bf.css">
  </head>
  <body>
    <div id="q-app" style="min-height: 60rem;"></div>
    
  </body>
</html>
HTML;
$loginId = createResource(0, 'Login2', $content, null, $modx);
//then we create the serviceManager
$content = <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite App</title>
    <script type="module" crossorigin src="/assets/components/cronos/22/js/index-3a1de505.js"></script>
    <link rel="stylesheet" href="/assets/components/cronos/22/css/index-f62128b0.css">
  </head>
  <body>
    <div id="q-app" style="min-height: 60rem;"></div>
    
  </body>
</html>
HTML;
//we get the resource group
$resource_group = $modx->getObject('modResourceGroup', ['name' => 'ServiceManager']);

createResource($loginId, 'serviceManager2', $content, $resource_group->get('id'), $modx);
