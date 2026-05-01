<?php

namespace Slendium\FrameworkTests\Base\Application;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use Slendium\Framework\Application\AssemblerException;
use Slendium\Framework\Application\Dependency;
use Slendium\Framework\Base\Application\InternalAssembler;
use Slendium\Framework\Base\Application\ReadOnlyDependency;
use Slendium\Framework\Base\Application\SingletonResolver;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class InternalAssemblerTest extends TestCase {

	public static function genericAssembleCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\ConstructorlessClass::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\EmptyConstructorClass::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\DependentClass::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\InterfaceWithImplementedBy::class ]) ];
		// Recursive class works until the recursive dependency is accessed
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\RecursiveDependencyClass::class ]) ];
	}

	#[DataProvider('genericAssembleCases')]
	public function test_assemble_shouldReturnObject_whenInvokedWithGenericDependency(Dependency $dependency): void {
		$sut = new InternalAssembler([ ]);

		$result = $sut->assemble($dependency);

		foreach ($dependency->constraints as $constraint) {
			$this->assertTrue(\is_a($result, $constraint));
		}
	}

	public static function assembleThrowCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ new ReadOnlyDependency(self::class, [ does_not_exist::class ]) ]; // @phpstan-ignore class.notFound (deliberate)
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\EmptyInterface::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\EmptyTrait::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\EmptyEnum::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\AbstractClass::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\PrivateConstructorClass::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\ProtectedConstructorClass::class ]) ];
		yield [ new ReadOnlyDependency(self::class, [ InternalAssemblerTest\AbstractClass::class, InternalAssemblerTest\EmptyInterface::class ]) ];
	}

	#[DataProvider('assembleThrowCases')]
	public function test_assemble_shouldThrow_whenInvokedWithInvalidDependency(Dependency $dependency): void {
		// Arrange
		$sut = new InternalAssembler([ ]);

		// Assert
		$this->expectException(AssemblerException::class);

		// Act
		$result = $sut->assemble($dependency);
	}

	public function test_assemble_shouldReturnObject_whenInvokedWithUnionDependencyWhereTheFirstTypeFailsButTheSecondSucceeds(): void {
		$dependency = new ReadOnlyDependency(self::class, [ InternalAssemblerTest\UnionDependencyClass::class ]);
		$sut = new InternalAssembler([ ]);

		$result = $sut->assemble($dependency);

		/** @var InternalAssemblerTest\UnionDependencyClass $result */
		$this->assertInstanceOf(InternalAssemblerTest\ConstructorlessClass::class, $result->parameter);
	}

	public function test_assemble_shouldReturnObject_whenInvokedWithIntersectionDependencyThatResolvesToSingleton(): void {
		$intersection = [ InternalAssemblerTest\EmptyInterface::class, InternalAssemblerTest\AbstractClass::class ];
		$dependency = new ReadOnlyDependency(self::class, $intersection);
		$resolver = new SingletonResolver(new InternalAssemblerTest\EmptyInterfaceImpl, $intersection);
		$sut = new InternalAssembler([ $resolver ]);

		$result = $sut->assemble($dependency);

		$this->assertInstanceOf(InternalAssemblerTest\EmptyInterface::class, $result);
		$this->assertInstanceOf(InternalAssemblerTest\AbstractClass::class, $result);
	}

	public function test_assemble_shouldReturnObject_whenInvokedWithSingleTypeOfIntersectionSingletonResolver(): void {
		$intersection = [ InternalAssemblerTest\EmptyInterface::class, InternalAssemblerTest\AbstractClass::class ];
		$resolver = new SingletonResolver(new InternalAssemblerTest\EmptyInterfaceImpl, $intersection);
		$dependencies = [
			new ReadOnlyDependency(self::class, [ InternalAssemblerTest\EmptyInterface::class ]),
			new ReadOnlyDependency(self::class, [ InternalAssemblerTest\AbstractClass::class ])
		];
		$sut = new InternalAssembler([ $resolver ]);

		$result = [ $sut->assemble($dependencies[0]), $sut->assemble($dependencies[1]) ];

		$this->assertInstanceOf($intersection[0], $result[0]);
		$this->assertInstanceOf($intersection[1], $result[1]);
	}

	/** Ensure the dependency tree is lazily instantiated, only instantiating the parts actually used. */
	public function test_assemble_shouldThrow_whenInvokedWithRecursiveDependencyButOnlyAfterRecursivePropertyIsAccessed(): void {
		// Arrange
		$dependency = new ReadOnlyDependency(self::class, [ InternalAssemblerTest\RecursiveDependencyClass::class ]);
		$assembler = new InternalAssembler([ ]);
		$sut = $assembler->assemble($dependency);

		// Assert
		$this->expectException(AssemblerException::class);

		// Act
		/** @var InternalAssemblerTest\RecursiveDependencyClass $sut */
		$_ = $sut->parent;
	}

}
