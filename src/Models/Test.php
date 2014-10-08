<?php

namespace Starter\Models;

use Fluxoft\Rebar\Db\Model;

class Test extends Model {
	protected $properties = array(
		'ID' => '',
		'Name' => ''
	);
	protected $propertyDbMap = array(
		'ID' => 'id',
		'Name' => 'testname'
	);
	// protected $sequence = 'tests_id_seq'; // pgsql only
	protected $dbTable = 'tests';
}