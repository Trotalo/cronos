<?php
/*
 * This file is part of VloX.
 *
 * Copyright (c) TROTALO, SAS. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
use MODX\Revolution\Rest\modRestController;
use MODX\Revolution\Rest\modRestServiceRequest;

class CronosFiles extends  \MODX\Revolution\Rest\modRestController {
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
    $workerId = $properties['worker_id'];
    $originalFileName =  basename($_FILES['file']['name']);
    $lastDot = strpos($originalFileName, '.');
    $extension = substr($originalFileName, $lastDot);
    $fileName = $workerId . '_' . round(microtime(true) * 1000) . $extension;
    $target = $this->modx->config['base_path'] . "assets/media/" . $fileName;
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target)) {
      return $this->success($target);
      //echo $fileName;
    } else {
      $this->failure("Failed to updload file!", null, 500);
    }
  }

}