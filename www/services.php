<?php
$c = new Fluxoft\Rebar\Container();

$c['config'] = function () {
	return false;
};

$c['dbreader'] = function (\Fluxoft\Rebar\Container $c) {
	$type = $c['config']['db']['reader']['type'];
	$host = $c['config']['db']['reader']['host'];
	$name = $c['config']['db']['reader']['name'];
	$user = $c['config']['db']['reader']['user'];
	$pass = $c['config']['db']['reader']['pass'];
	$conn = new \PDO(
		"$type:host=$host;dbname=$name",
		$user,
		$pass,
		array(\PDO::ATTR_PERSISTENT => false)
	);
	return new \Fluxoft\Rebar\Db\Providers\MySql($conn);
};
$c['dbwriter'] = $c['dbreader']; // most of the time the same db connection will be used

/**
 * @param $c
 * @return \Fluxoft\Rebar\Db\ModelFactory
 */
$c['ModelFactory'] = function ($c) {
	return new Fluxoft\Rebar\Db\ModelFactory(
		$c['dbreader'],
		$c['dbwriter'],
		'\\'.$c['config']['app']['namespace'].'\\Models\\'
	);
};

/**
 * @param $c
 * @return \Fluxoft\Rebar\Auth\UserFactory
 */
$c['UserFactory'] = function ($c) {
	return new Fluxoft\Rebar\Auth\UserFactory(
		'\\'.$c['config']['app']['namespace'].'\\Models\\WebUser',
		$c['dbreader'],
		$c['dbwriter']
	);
};

/**
 * @param $c
 * @return \Fluxoft\Rebar\Auth\Web
 */
$c['WebAuth'] = function ($c) {
	return new Fluxoft\Rebar\Auth\Web(
		$c['UserFactory'],
		array(
			'RememberMe' => array(
				'Enabled' => true,
				'CookieDomain' => 'dev.initiatel'
			),
			'SessionExtras' => array(
				'FirstName',
				'LastName'
			)
		)
	);
};

// Smarty
$c['templatePath'] = __DIR__.'/templates/';
$c['cachePath'] = __DIR__.'/../cache/';
/**
 * @param $c
 * @return \Fluxoft\Rebar\Presenters\Smarty
 */
$c['smarty'] = function ($c) {
	return new \Fluxoft\Rebar\Presenters\Smarty(
		$c['templatePath'],
		$c['cachePath'] . 'Smarty/templates_c',
		$c['cachePath'] . 'Smarty/cache',
		$c['cachePath'] . 'Smarty/config'
	);
};

/**
 * @param $c
 * @return \Fluxoft\Rebar\Presenters\Twig
 */
$c['twig'] = function ($c) {
	return new \Fluxoft\Rebar\Presenters\Twig(
		$c['templatePath'],
		$c['cachePath'] . 'Twig/cache'
	);
};

/**
 * @param $c
 * @return \Fluxoft\Rebar\Presenters\Phtml
 */
$c['phtml'] = function ($c) {
	return new \Fluxoft\Rebar\Presenters\Phtml(
		$c['templatePath']
	);
};
return $c;