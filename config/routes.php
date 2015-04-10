<?php
use Cake\Routing\Router;

Router::plugin('Messages', function ($routes) {
        Router::extensions('rss');
        $routes->connect('/', array('controller' => 'users', 'action' => 'signin'));
        $routes->connect('/messages', array('controller' => 'messages'));
        $routes->connect('/messages/index/*', array('controller' => 'messages'));
        $routes->connect('/messages/compose/*', array('controller' => 'messages', 'action' => 'compose'));
        $routes->connect('/messages/delete/*', array('controller' => 'messages', 'action' => 'delete'));
        $routes->connect('/messages/view/*', array('controller' => 'messages', 'action' => 'view'));
        $routes->connect('/messages/reply/*', array('controller' => 'messages', 'action' => 'reply'));
        $routes->connect('/messages/forward/*', array('controller' => 'messages', 'action' => 'forward'));
        //$routes->connect('/messages/*', array('controller' => 'messages', 'action' => 'display'));
        $routes->fallbacks('InflectedRoute');
});