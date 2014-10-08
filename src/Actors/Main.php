<?php

namespace Starter\Models;

use Fluxoft\Rebar\Actor;
use Fluxoft\Rebar\Auth\Web;
use Fluxoft\Rebar\Container;

class Main extends Actor {
	public function Authenticate(Web $webAuth, $method) {
		$webAuth->AutoLogin();
		switch ($method) {
			case 'Auth':
				return $webAuth->IsLoggedIn();
				break;
		}
		return true;
	}

	public function Index(Container $c) {
		/** @var \Fluxoft\Rebar\Presenters\Smarty presenter */
		$this->presenter = $c['smarty'];
		$this->presenter->Layout = '/layout.html';
		$this->presenter->Template = '/main/index.html';

		$this->Set('pageTitle', 'Welcome');
	}

	public function Auth(Container $c) {
		$this->Set('auth', $c['WebAuth']->IsLoggedIn());
	}
} 