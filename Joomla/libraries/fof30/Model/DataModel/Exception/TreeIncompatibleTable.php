<?php
/**
 * @package     FOF
 * @copyright   Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Model\DataModel\Exception;

use Exception;

defined('_JEXEC') or die;

class TreeIncompatibleTable extends \UnexpectedValueException
{
	public function __construct( $tableName, $code = 500, Exception $previous = null )
	{
		$message = \JText::sprintf('LIB_FOF_MODEL_ERR_TREE_INCOMPATIBLETABLE', $tableName);

		parent::__construct( $message, $code, $previous );
	}

}