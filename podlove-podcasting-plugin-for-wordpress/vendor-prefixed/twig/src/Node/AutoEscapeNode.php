<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\Node;

use PodlovePublisher_Vendor\Twig\Attribute\YieldReady;
use PodlovePublisher_Vendor\Twig\Compiler;
/**
 * Represents an autoescape node.
 *
 * The value is the escaping strategy (can be html, js, ...)
 *
 * The true value is equivalent to html.
 *
 * If autoescaping is disabled, then the value is false.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
#[\Twig\Attribute\YieldReady]
class AutoEscapeNode extends Node
{
    public function __construct($value, Node $body, int $lineno)
    {
        parent::__construct(['body' => $body], ['value' => $value], $lineno);
    }
    public function compile(Compiler $compiler) : void
    {
        $compiler->subcompile($this->getNode('body'));
    }
}
