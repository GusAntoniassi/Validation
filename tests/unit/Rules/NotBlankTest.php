<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @group  rule
 * @covers \Respect\Validation\Exceptions\NotBlankException
 * @covers \Respect\Validation\Rules\NotBlank
 */
class NotBlankTest extends TestCase
{
    /**
     * @dataProvider providerForNotBlank
     *
     * @test
     */
    public function shouldValidateWhenNotBlank($input): void
    {
        $rule = new NotBlank();

        self::assertTrue($rule->validate($input));
    }

    /**
     * @dataProvider providerForBlank
     *
     * @test
     */
    public function shouldNotValidateWhenBlank($input): void
    {
        $rule = new NotBlank();

        self::assertFalse($rule->validate($input));
    }

    /**
     * @expectedException \Respect\Validation\Exceptions\NotBlankException
     * @expectedExceptionMessage The value must not be blank
     *
     * @test
     */
    public function shouldThrowExceptionWhenFailure(): void
    {
        $rule = new NotBlank();
        $rule->check(0);
    }

    /**
     * @expectedException \Respect\Validation\Exceptions\NotBlankException
     * @expectedExceptionMessage whatever must not be blank
     *
     * @test
     */
    public function shouldThrowExceptionWhenFailureAndDoesHaveAName(): void
    {
        $rule = new NotBlank();
        $rule->setName('whatever');
        $rule->check(0);
    }

    public function providerForNotBlank()
    {
        $object = new stdClass();
        $object->foo = true;

        return [
            [1],
            [' oi'],
            [[5]],
            [[1]],
            [$object],
        ];
    }

    public function providerForBlank()
    {
        return [
            [null],
            [''],
            [[]],
            [' '],
            [0],
            ['0'],
            [0],
            ['0.0'],
            [false],
            [['']],
            [[' ']],
            [[0]],
            [['0']],
            [[false]],
            [[[''], [0]]],
            [new stdClass()],
        ];
    }
}
