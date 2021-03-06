<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Parser\TokenParser\Chainable;

use Nelmio\Alice\Definition\Value\FixturePropertyValue;
use Nelmio\Alice\Definition\ValueInterface;
use Nelmio\Alice\Exception\FixtureBuilder\ExpressionLanguage\ParseException;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\Token;
use Nelmio\Alice\FixtureBuilder\ExpressionLanguage\TokenType;

final class PropertyReferenceTokenParser extends AbstractChainableParserAwareParser
{
    /**
     * @inheritdoc
     */
    public function canParse(Token $token): bool
    {
        return $token->getType() === TokenType::PROPERTY_REFERENCE_TYPE;
    }

    /**
     * Parses tokens values like "@user->username".
     *
     * {@inheritdoc}
     *
     * @throws ParseException
     */
    public function parse(Token $token): FixturePropertyValue
    {
        parent::parse($token);

        $explodedValue = explode('->', $token->getValue());
        if (count($explodedValue) !== 2) {
            throw ParseException::createForToken($token);
        }

        $reference = $this->parser->parse($explodedValue[0]);
        if (false === $reference instanceof ValueInterface) {
            throw ParseException::createForToken($token);
        }

        return new FixturePropertyValue(
            $reference,
            $explodedValue[1]
        );
    }
}
