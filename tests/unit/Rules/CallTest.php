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
use Respect\Validation\Validatable;

/**
 * @group  rule
 * @covers \Respect\Validation\Exceptions\CallException
 * @covers \Respect\Validation\Rules\Call
 */
class CallTest extends TestCase
{
    private const CALLBACK_RETURN = [];

    public function thisIsASampleCallbackUsedInsideThisTest()
    {
        return self::CALLBACK_RETURN;
    }

    /**
     * @test
     */
    public function callbackValidatorShouldAcceptEmptyString(): void
    {
        $validatable = $this->createMock(Validatable::class);
        $validatable
            ->expects(self::once())
            ->method('assert')
            ->with(['']);

        $v = new Call('str_split', $validatable);
        $v->assert('');
    }

    /**
     * @test
     */
    public function callbackValidatorShouldAcceptStringWithFunctionName(): void
    {
        $validatable = $this->createMock(Validatable::class);
        $validatable
            ->expects(self::once())
            ->method('assert')
            ->with(['t', 'e', 's', 't']);

        $v = new Call('str_split', $validatable);
        $v->assert('test');
    }

    /**
     * @test
     */
    public function callbackValidatorShouldAcceptArrayCallbackDefinition(): void
    {
        $validatable = $this->createMock(Validatable::class);
        $validatable
            ->expects(self::once())
            ->method('assert')
            ->with(self::CALLBACK_RETURN);

        $v = new Call([$this, 'thisIsASampleCallbackUsedInsideThisTest'], $validatable);
        $v->assert('test');
    }

    /**
     * @test
     */
    public function callbackValidatorShouldAcceptClosures(): void
    {
        $return = [];

        $validatable = $this->createMock(Validatable::class);
        $validatable
            ->expects(self::once())
            ->method('assert')
            ->with($return);

        $v = new Call(
            function () use ($return) {
                return $return;
            },
            $validatable
        );
        $v->assert('test');
    }

    /**
     * @expectedException \Respect\Validation\Exceptions\CallException
     *
     * @test
     */
    public function callbackFailedShouldThrowCallException(): void
    {
        $v = new Call('strrev', new ArrayVal());
        self::assertFalse($v->validate('test'));
        $v->assert('test');
    }
}
