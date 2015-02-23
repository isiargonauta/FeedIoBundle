<?php

/**
 * FeedIoBundle
 *
 * @package FeedIoBundle/Adapter
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2015, Alexandre Debril
 *
 */

namespace Debril\FeedIoBundle\Adapter;


/**
 * Class FeedNotFoundException
 * Thrown when storage cannot found the requested feed
 * @package Debril\FeedIoBundle\Adapter
 */
class FeedNotFoundException extends \OutOfBoundsException {}