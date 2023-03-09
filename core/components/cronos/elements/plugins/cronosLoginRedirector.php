<?php
$eventName = $modx->event->name;

if ($eventName === "OnWebLogin") {
  $serviceManager = $modx->makeUrl(22);
  $supervisorPage = $modx->makeUrl(23);
  if ($modx->user->isMember('ServiceManager') || $modx->user->get('sudo')) {
    //$modx->sendRedirect($serviceManager);
    return $serviceManager;
  }
  if ($modx->user->isMember('Supervisor')) {
    //$modx->sendRedirect($supervisorPage);
    return $supervisorPage;
  }
} else {
  return;
}