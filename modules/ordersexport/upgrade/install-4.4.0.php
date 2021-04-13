<?php

if (!defined('_PS_VERSION_'))
  exit;

function upgrade_module_4_4_0($object)
{
  return $object->upgradeExport_4_4_0();
}
