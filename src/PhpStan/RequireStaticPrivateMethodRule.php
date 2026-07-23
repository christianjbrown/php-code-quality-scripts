<?php

declare(strict_types=1);

namespace ChristianBrown\CodeQualityScripts\PhpStan;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function is_string;
use function sprintf;

/**
 * A private method that never uses `$this` is stateless and should be `static`.
 * Constructors/destructors, already-static methods, and PHPUnit test classes are
 * exempt. (Interface and parent-override methods are inherently public/protected,
 * so limiting this to private methods needs no such exemption.).
 *
 * @implements Rule<InClassMethodNode>
 */
final class RequireStaticPrivateMethodRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @param InClassMethodNode $node
     *
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $method = $node->getMethodReflection();
        if (!$method->isPrivate()) {
            return [];
        }
        if ($method->isStatic()) {
            return [];
        }

        $name = $method->getName();
        if ('__construct' === $name) {
            return [];
        }
        if ('__destruct' === $name) {
            return [];
        }
        if ($node->getClassReflection()->isSubclassOf('PHPUnit\Framework\TestCase')) {
            return [];
        }
        if (self::usesThis($node->getOriginalNode())) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf('Private method %s() does not use $this and should be static.', $name))
                ->identifier('christianBrown.staticPrivateMethod')
                ->build(),
        ];
    }

    private static function usesThis(ClassMethod $method): bool
    {
        $usage = (new NodeFinder())->findFirst(
            (array) $method->stmts,
            static fn (Node $node): bool => $node instanceof Variable && is_string($node->name) && 'this' === $node->name
        );

        return null !== $usage;
    }
}
