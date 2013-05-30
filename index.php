<?php

use Nette\Config\Configurator,
	Nette\Application\Routers\Route;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/lib/TexyFactory.php';


// Configure Application
$configurator = new Configurator;
$configurator->enableDebugger(__DIR__ . '/log');
$configurator->setTempDirectory(__DIR__ . '/tmp');
$configurator->addConfig(__DIR__ . '/config.neon', Configurator::NONE);
$container = $configurator->createContainer();

// Setup Router
$container->router[] = new Route('', function($presenter) {
	return readfile(__DIR__ . '/html/index.html');
});
$container->router[] = new Route('texy', function($presenter) use ($container) {
	$request = $presenter->request;
	if ($request->method == 'POST' && isset($request->post['texy'])) {
		return $container->textPreprocessor->process($presenter->request->post['texy']);
	} else {
		return "Invalid request";
	}
});


// Run the application!
$container->application->run();
