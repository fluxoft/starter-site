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
$c['appPath'] = '/var/www';
$c['templatePath'] = $c['appPath'] . '/templates';
$c['cachePath'] = '/var/www/cache';
/**
 * @param $c
 * @return Fluxoft\Rebar\Presenters\Smarty
 */
$c['smarty'] = function ($c) {
	$smarty = new \Smarty();
	$smarty->muteExpectedErrors();
	$smarty->template_dir = $c['templatePath'];
	$smarty->compile_dir = $c['cachePath'] . '/Smarty/templates_c';
	$smarty->cache_dir = $c['cachePath'] . '/Smarty/cache';
	$smarty->config_dir = $c['cachePath'] . '/Smarty/config';

	return new \Fluxoft\Rebar\Presenters\Smarty(
		$smarty,
		$c['templatePath']
	);
};
return $c;