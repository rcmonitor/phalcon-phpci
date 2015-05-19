<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Dispatcher as MvcDispatcher,
	Phalcon\Events\Manager as EventsManager;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter($config->toArray());
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});


$di->set('fileLogger', function() use ($config) {
	return new \Phalcon\Logger\Adapter\File($config->application->logDir . 'app.log');
});


$di->set('dispatcher', function() use($di){
	// Создание менеджера событий
	$eventsManager = new EventsManager();

	// Прикрепление функции-слушателя для событий типа "dispatch"
	$eventsManager->attach("dispatch:beforeExecuteRoute", function(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher) use ($di) {
		$oLogger = $di->get('fileLogger');
//		$oLogger->info('dispatch happened with ' .
//			get_class($event) . ' event; ' .
//			get_class($dispatcher) . ' dispatcher'
//			);

		$oPreController = new BasePreController($di);
		$oPreController->handle($event, $dispatcher);

//		$oLogger->info('fired');
//		$oLogger->info($event->getType() . ' data: ' . $event->getData());
//		$oLogger->info($dispatcher->getControllerName() . '::' . $dispatcher->getActionName());

	});

	$dispatcher = new MvcDispatcher();

	// Связывание менеджера событий с диспетчером
	$dispatcher->setEventsManager($eventsManager);

	return $dispatcher;
});

//$di->set('dispatcher', array(
//		'className' => 'BasePreController',
//		'calls' => array(
//			'method' => 'register'
//		),
//
////	new BasePreController($di), 'register')
//	));

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
