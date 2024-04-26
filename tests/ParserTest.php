<?php

declare(strict_types=1);

use Oblak\SrbUtils\NumberScale;
use Oblak\SrbUtils\NumbersToSerbianWords;
use Oblak\SrbUtils\Tests\TestBase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(NumbersToSerbianWords::class)]
#[CoversClass(NumberScale::class)]
class ParserTest extends TestBase
{
    private NumbersToSerbianWords $parser;

    public function setUp(): void
    {
        $this->parser = new NumbersToSerbianWords();
    }

    public function testSeparateDigits()
    {
        $this->assertEquals(
            [ 111, 222, 333 ],
            $this->invokePrivateMethod($this->parser, 'separateDigits', [ 111222333 ]),
        );
        $this->assertEquals(
            [ 222, 333 ],
            $this->invokePrivateMethod($this->parser, 'separateDigits', [ 222333 ]),
        );
        $this->assertEquals([ 3 ], $this->invokePrivateMethod($this->parser, 'separateDigits', [ 3 ]));
        $this->assertEquals([ 0 ], $this->invokePrivateMethod($this->parser, 'separateDigits', [ 0 ]));
        $this->assertEquals([ 1 ], $this->invokePrivateMethod($this->parser, 'separateDigits', [ 1 ]));
        $this->assertEquals(
            [ 1, 0 ],
            $this->invokePrivateMethod($this->parser, 'separateDigits', [ 1000 ]),
        );
    }

    public function testConversion()
    {
        $this->assertEquals('nula', $this->parser->toWordString(0));
        $this->assertEquals(
            [ 'jedna hiljada', 'sto jedan' ],
            $this->parser->toWordArray(1101),
        );

        $this->assertEquals(
            'jedna hiljada sto jedan',
            $this->parser->toWordString(1101),
        );

        $this->assertEquals(
            'hiljadu',
            $this->parser->useAccusative(true)->toWordString(1000),
        );

        $this->assertEquals(
            'sto jedan',
            $this->parser->useAccusative(true)->toWordString(101),
        );

        $this->assertEquals(
            'jedan bilion',
            $this
                ->parser
                ->useAccusative(true)
                ->useScale(NumberScale::Short)
                ->toWordString(1000000000),
        );

        $this->assertEquals(
            'jedan',
            $this->parser->toWordString(new class {
                public function __toString(): string
                {
                    return '1';
                }
            }),
        );

        $this->assertEquals(
            'jednaaest milijardi sto dvanaest miliona sto trideset jedna hiljada dvesta dvadeset jedan',
            $this->parser
                ->useAccusative(false)
                ->useScale(NumberScale::Long)->toWordString(11112131221),
        );
    }
}
