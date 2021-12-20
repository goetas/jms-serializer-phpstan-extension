<?php declare(strict_types=1);

namespace Goetas\JmsSerializerPhpstanExtension;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Type\Parser;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\TypeStringResolver;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class SerializerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    private $class;

    private $method;

    private $typeStringResolver;

    public function __construct(TypeStringResolver $typeStringResolver, string $class, string $method)
    {
        $this->typeStringResolver = $typeStringResolver;
        $this->method = $method;
        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === $this->method;
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (!isset($methodCall->args[1])) {
            return new MixedType();
        }

        $argType = $scope->getType($methodCall->args[1]->value);
        if (!$argType instanceof ConstantStringType) {
            return new MixedType();
        }

        $objectName = $argType->getValue();

        return $this->getType($objectName);
    }

    private function getType(string $objectName): Type
    {
        $parser = new Parser();
        return $this->getRealType($parser->parse($objectName));
    }

    private function getRealType(array $type): Type
    {
        if ($type['name'] === 'array') {
            $keyType = new MixedType(false);
            $valueType = new MixedType(false);
            if (count($type['params']) === 1) {
                $valueType = $this->getRealType($type['params'][0]);
            } elseif (count($type['params']) === 2) {
                $keyType = $this->getRealType($type['params'][0]);
                $valueType = $this->getRealType($type['params'][1]);
            }

            return new ArrayType($keyType, $valueType);
        } else {
            return $this->typeStringResolver->resolve($type['name']);
        }
    }
}
