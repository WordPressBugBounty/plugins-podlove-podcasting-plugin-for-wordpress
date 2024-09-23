<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\TokenParser;

use PodlovePublisher_Vendor\Twig\Node\Expression\TempNameExpression;
use PodlovePublisher_Vendor\Twig\Node\Node;
use PodlovePublisher_Vendor\Twig\Node\PrintNode;
use PodlovePublisher_Vendor\Twig\Node\SetNode;
use PodlovePublisher_Vendor\Twig\Token;
/**
 * Applies filters on a section of a template.
 *
 *   {% apply upper %}
 *      This text becomes uppercase
 *   {% endapply %}
 *
 * @internal
 */
final class ApplyTokenParser extends AbstractTokenParser
{
    public function parse(Token $token) : Node
    {
        $lineno = $token->getLine();
        $name = $this->parser->getVarName();
        $ref = new TempNameExpression($name, $lineno);
        $ref->setAttribute('always_defined', \true);
        $filter = $this->parser->getExpressionParser()->parseFilterExpressionRaw($ref, $this->getTag());
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideApplyEnd'], \true);
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);
        return new Node([new SetNode(\true, $ref, $body, $lineno, $this->getTag()), new PrintNode($filter, $lineno, $this->getTag())]);
    }
    public function decideApplyEnd(Token $token) : bool
    {
        return $token->test('endapply');
    }
    public function getTag() : string
    {
        return 'apply';
    }
}
