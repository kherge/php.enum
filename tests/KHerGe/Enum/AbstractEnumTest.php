<?php

declare(strict_types=1);

namespace Tests\KHerGe\Enum;

use KHerGe\Enum\AbstractEnum;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Verifies that the abstract enum implementation functions as intended.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class AbstractEnumTest extends TestCase
{
    /**
     * Verify that an instance of the enum is returned for a variant.
     */
    public function testCreateInstanceOfVariant()
    {
        $variant = Example::ONE();

        self::assertInstanceOf(Example::class, $variant, 'The correct class was not instantiated.');
    }

    /**
     * @depends testCreateInstanceOfVariant
     *
     * Verify that the enum variant maps are built properly.
     */
    public function testMapsBuiltProperly()
    {
        self::assertEquals(
            [
                'maps' => [
                    'name' => [
                        Example::class => [
                            'ONE' => 1,
                            'TWO' => 2,
                        ]
                    ],
                    'value' => [
                        Example::class => [
                            1 => 'ONE',
                            2 => 'TWO',
                        ]
                    ]
                ]
            ],
            (new ReflectionClass(AbstractEnum::class))->getStaticProperties(),
            'The map must be built properly.'
        );
    }

    /**
     * @depends testCreateInstanceOfVariant
     *
     * Verify that the variant arguments are returned.
     */
    public function testGetVariantArguments()
    {
        $variant = Example::ONE('a', 'b', 'c');

        self::assertEquals(['a', 'b', 'c'], $variant->getArguments(), 'The arguments must be returned.');
    }

    /**
     * @depends testCreateInstanceOfVariant
     *
     * Verify that the name of the variant is returned.
     */
    public function testGetVariantName()
    {
        $variant = Example::ONE();

        self::assertEquals('ONE', $variant->getName(), 'The name must be returned.');
    }

    /**
     * @depends testCreateInstanceOfVariant
     *
     * Verify that the value of the variant is returned.
     */
    public function testGetVariantValue()
    {
        $variant = Example::ONE();

        self::assertEquals(1, $variant->getValue(), 'The value must be returned.');
    }

    /**
     * @depends testCreateInstanceOfVariant
     *
     * Verify that two variants are compared, ignoring variant arguments.
     */
    public function testCompareVariantsWithoutArguments()
    {
        $left = Example::ONE();
        $right = Example::ONE('a', 'b', 'c');

        self::assertTrue($left->is($right), 'The two variants must be equal.');

        $right = Example::TWO();

        self::assertFalse($left->is($right), 'The two variants must not be equal.');
    }

    /**
     * @depends testCreateInstanceOfVariant
     *
     * Verify that two variants are compared, including variant arguments.
     */
    public function testCompareVariantsWithArguments()
    {
        $left = Example::ONE('a', 'b', 'c');
        $right = Example::ONE('a', 'b', 'c');

        self::assertTrue($left->isExactly($right), 'The two variants must be equal.');

        $right = Example::ONE();

        self::assertFalse($left->isExactly($right), 'The two variants must not be equal.');
    }

    /**
     * Verify that the name of a variant is returned for its value.
     */
    public function testGetNameForValue()
    {
        self::assertEquals('ONE', Example::nameOf(1), 'The name must be returned.');
    }

    /**
     * Verify that the value of a variant is returned for its name.
     */
    public function testGetValueForName()
    {
        self::assertEquals(1, Example::valueOf('ONE'), 'The value must be returned.');
    }
}