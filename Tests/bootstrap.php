<?php
/**
 * FeedIo Bundle for Symfony 2
 *
 * @package FeedIoBundle\Tests
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */
 
$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->addPsr4('Debril\\FeedIoBundle\\', __DIR__);
$loader->addPsr4('FeedIo\\', __DIR__);

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
