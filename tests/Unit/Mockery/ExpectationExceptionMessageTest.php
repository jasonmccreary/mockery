<?php

declare(strict_types=1);

namespace Tests\Unit\Mockery;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHP73\ClassWithStaticMethods;

use Stubs\Set;
use function mock;

/**
 * @coversDefaultClass \Mockery
 */
final class ExpectationExceptionMessageTest extends MockeryTestCase
{
    public function testMessagingForMissingExpectation(): void
    {
        $mock = mock(Set::class);

        $this->expectException(\Mockery\Exception\BadMethodCallException::class);
        $this->expectExceptionMessage('Mockery_0_Stubs_Set received a call to foo() which it did not expect. Did you mean to set an expectation for this method call?');

        self::assertTrue($mock->foo());

        $this->closeMockery();
    }

    public function testMessagingForMissingExpectationWithArgs(): void
    {
        $mock = mock(Set::class);

        $this->expectException(\Mockery\Exception\BadMethodCallException::class);
        $this->expectExceptionMessage('Mockery_0_Stubs_Set received a call to foo() which it did not expect. Did you mean to set an expectation for this method call?');

        self::assertTrue($mock->foo(123));

        $this->closeMockery();
    }

    public function testMessagingForSingleExpectationWithUnmatchedArgs(): void
    {
        $mock = mock(Set::class);
        $mock->shouldReceive('add')
            ->with(1);

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        $this->expectExceptionMessage('Mockery received a call to Mockery_0_Stubs_Set::add(-1) which it did not expect. Mockery expected a call to Mockery_0_Stubs_Set::add(1)' . PHP_EOL . PHP_EOL);

        self::assertNull($mock->add(-1));

        $this->closeMockery();
    }

//    public function testMessagingForMultipleExpectationsWithAllUnmatched(): void
//    {
//        $this->markTestSkipped('shouldReceive does not require a call to be made, so this setup is impossible at this time');
//
//        $mock = mock(Set::class);
//        $mock->shouldReceive('add')
//            ->with(1);
//        $mock->shouldReceive('add')
//            ->with(2);
//
//        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
//        $this->expectExceptionMessage('Mockery received a call to Mockery_0_Stubs_Set::add(-1) which it did not expect.' . PHP_EOL . PHP_EOL);
//
//        $mock->add(1);
//
//        self::assertNull($mock->add(-1));
//
//          $this->closeMockery();
//    }

    public function testMessagingForMultipleExpectationsWithMatchingAndUnmatched(): void
    {
        $mock = mock(Set::class);
        $mock->shouldReceive('add')
            ->with(1);
        $mock->shouldReceive('add')
            ->with(2);

        $this->expectException(\Mockery\Exception\NoMatchingExpectationException::class);
        $this->expectExceptionMessage('Mockery received a call to Mockery_0_Stubs_Set::add(-2) which it did not expect. Mockery expected a call to Mockery_0_Stubs_Set::add(2)' . PHP_EOL . PHP_EOL);

        $mock->add(1);

        self::assertNull($mock->add(-2));

        $this->closeMockery();
    }

    public function testMessagingForExpectationsExactCount(): void
    {
        $mock = mock(Set::class);
        $mock->expects('add')
            ->with(1);

        $this->expectException(\Mockery\Exception\InvalidCountException::class);
        $this->expectExceptionMessage('Mockery expected Mockery_0_Stubs_Set::add(1) to be called exactly 1 time, but it was called 0 times.');

        $this->closeMockery();
    }

    public function testMessagingForExpectationsAtLeastCount(): void
    {
        $mock = mock(Set::class);
        $mock->expects('add')
            ->atLeast()
            ->times(2)
            ->with(1);

        $this->expectException(\Mockery\Exception\InvalidCountException::class);
        $this->expectExceptionMessage('Mockery expected Mockery_0_Stubs_Set::add(1) to be called at least 2 times, but it was called 1 time.');

        $mock->add(1);

        $this->closeMockery();
    }

    public function testMessagingForExpectationsAtMostCount(): void
    {
        $mock = mock(Set::class);
        $mock->expects('add')
            ->with(1)
            ->atMost()
            ->times(1);

        $this->expectException(\Mockery\Exception\InvalidCountException::class);
        $this->expectExceptionMessage('Mockery expected Mockery_0_Stubs_Set::add(1) to be called at most 1 time, but it was called 2 times.');

        $mock->add(1);
        $mock->add(1);

        $this->closeMockery();
    }
}
