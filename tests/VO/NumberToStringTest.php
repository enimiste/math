<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 13/07/2016
 * Time: 12:25
 */

namespace Test\Enimiste\Math\VO;


use Enimiste\Math\VO\FloatNumber as FN;
use Enimiste\Math\VO\IntegerNumber as IN;
use Enimiste\Math\VO\Number as N;
use Test\Enimiste\Math\TestCase;

class NumberToStringTest extends TestCase {

	public function numbers() {
		return [
			[ new FN( 1 ), '1.00' ],
			[ new FN( - 1 ), '-1.00' ],
			[ new FN( 0 ), '0.00' ],
			[ new FN( 11 ), '11.00' ],
			[ new FN( 21.98 ), '21.98' ],
			[ new FN( 1002.9 ), '1002.90' ],
			[ new FN( 21.987 ), '21.99' ],
			[ new FN( 21.985 ), '21.99' ],
			[ new FN( 21.984 ), '21.98' ],
			[ new FN( - 21.98 ), '-21.98' ],
			[ new FN( - 21.987 ), '-21.99' ],
			[ new FN( - 21.984 ), '-21.98' ],
			[ new FN( - 21.985 ), '-21.99' ],
			[ new FN( 10 / 3 ), '3.33' ],
			[ new FN( 1.0 * 2 ), '2.00' ],
			[ new FN( 1.0 / 2 ), '0.50' ],
			[ new IN( 0 ), '0' ],
			[ new IN( 10 ), '10' ],
			[ new IN( - 10 ), '-10' ],
		];
	}

	/**
	 * @test
	 * @dataProvider numbers
	 */
	public function to_string_numbers( N $i, $expected ) {
		$this->assertSame( $expected, $i->__toString() );
	}
}