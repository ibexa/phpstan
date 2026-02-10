<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Property>
 */
final readonly class RequireMockObjectInPropertyTypeRule implements Rule
{
    public function getNodeType(): string
    {
        return Property::class;
    }

    /**
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->type === null) {
            return [];
        }

        if (!$this->docCommentIncludesMockObject($node)) {
            return [];
        }

        if ($this->typeNodeIncludesMockObject($node->type)) {
            return [];
        }

        return [
            RuleErrorBuilder::message('Property typed as MockObject only in PHPDoc. Use intersection type with MockObject.')
                ->identifier('Ibexa.requireMockObjectPropertyType')
                ->build(),
        ];
    }

    private function typeNodeIncludesMockObject(Node $type): bool
    {
        if ($type instanceof NullableType) {
            return $this->typeNodeIncludesMockObject($type->type);
        }

        if ($type instanceof UnionType || $type instanceof IntersectionType) {
            foreach ($type->types as $innerType) {
                if ($this->typeNodeIncludesMockObject($innerType)) {
                    return true;
                }
            }

            return false;
        }

        if ($type instanceof Identifier) {
            return $type->toString() === 'MockObject';
        }

        if ($type instanceof Name) {
            return $type->getLast() === 'MockObject';
        }

        return false;
    }

    private function docCommentIncludesMockObject(Property $property): bool
    {
        $docComment = $property->getDocComment();
        if ($docComment === null) {
            return false;
        }

        return preg_match('/@var\\s+[^\\n]*MockObject/', $docComment->getText()) === 1;
    }
}
