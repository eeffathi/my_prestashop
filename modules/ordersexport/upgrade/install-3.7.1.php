<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_7_1($object)
{
  return $object->upgradeExport_3_7_1();
}


