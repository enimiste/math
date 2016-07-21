<?php
namespace Test\Enimiste\Math\BcMath;

use Enimiste\Math\BcMath\BcMathCalculator;
use Enimiste\Math\Contracts\Calculator;
use Enimiste\Math\VO\FloatNumber;
use Enimiste\Math\VO\FloatNumber as FN;
use Enimiste\Math\VO\IntegerNumber as IN;
use Test\Enimiste\Math\TestCase;

class BcMathCalculatorTest extends TestCase {
	/** @var  Calculator */
	protected $calc;

	/**
	 * @before
	 */
	public function setCalculator() {
		$this->calc = new BcMathCalculator();
	}

	public function add_good_numbers() {
		return [
			'0'  => [ new IN( 0 ), new IN( 0 ), 0, IN::class, 2 ],
			'1'  => [ new IN( 1 ), new IN( 2 ), 3, IN::class, 2 ],
			'2'  => [ new FN( 1 ), new IN( 2 ), 3.0, FN::class, 2 ],
			'3'  => [ new IN( 1000 ), new FN( 2.9 ), 1002.90, FN::class, 2 ],
			'4'  => [ new IN( 10 ), new FN( 20.98 ), 30.98, FN::class, 2 ],
			'5'  => [ new IN( - 10 ), new IN( 20 ), 10, IN::class, 2 ],
			'6'  => [ new IN( 10 ), new IN( - 20 ), - 10, IN::class, 2 ],
			'7'  => [ new IN( - 10 ), new IN( - 20 ), - 30, IN::class, 2 ],
			'8'  => [ new FN( - 10 ), new IN( - 20 ), - 30.0, FN::class, 2 ],
			'9'  => [ new IN( - 10 ), new FN( - 2.87 ), - 12.87, FN::class, 2 ],
			'10' => [ new IN( '-10' ), new FN( - 2.87 ), - 12.87, FN::class, 2 ],
			'11' => [ new FN( 12.0003, 4 ), new FN( 11.0304, 4 ), 23.0307, FN::class, 4 ],//23.0307
			'12' => [ new FN( 15.0003, 4 ), new FN( 11.04 ), 26.04, FN::class, 2 ],//23.0403
		];
	}

	/**
	 * @test
	 * @dataProvider add_good_numbers
	 * @group        add_to_good_numbers
	 */
	public function add_to_good_numbers( $l, $r, $expected, $clazz, $scale ) {
		$res = $this->calc->add( $l, $r );
		$this->assertInstanceOf( $clazz, $res );
		$this->assertTrue( $res->equals( $expected, $scale ) );
		if ( $res instanceof FloatNumber ) {
			$this->assertEquals( $scale, $res->getScale() );
		}
	}

	public function mult_good_numbers() {
		return [
			'1'  => [ new IN( 1 ), new IN( 2 ), 2, IN::class, 2 ],
			'2'  => [ new FN( 1 ), new IN( 2 ), 2.0, FN::class, 2 ],
			'3'  => [ new IN( 1000 ), new FN( 2.9 ), 2900.0, FN::class, 2 ],
			'4'  => [ new IN( 10 ), new FN( 20.98 ), 209.8, FN::class, 2 ],
			'5'  => [ new IN( - 10 ), new IN( 20 ), - 200, IN::class, 2 ],
			'6'  => [ new FN( - 10 ), new IN( 20 ), - 200.0, FN::class, 2 ],
			'7'  => [ new IN( - 10 ), new IN( - 20 ), 200, IN::class, 2 ],
			'8'  => [ new FN( - 10 ), new IN( - 20 ), 200.0, FN::class, 2 ],
			'9'  => [ new IN( - 10 ), new FN( - 2.87 ), 28.7, FN::class, 2 ],
			'10' => [ new IN( '-10' ), new FN( - 2.87 ), 28.7, FN::class, 2 ],
			'11' => [ new IN( 111 ), new FN( 0 ), 0.0, FN::class, 2 ],
			'12' => [ new IN( 111 ), new IN( 0 ), 0, IN::class, 2 ],
			'13' => [ new FN( 12.0003, 4 ), new FN( 11.0304, 4 ), 132.3681, FN::class, 4 ],//132,36810912
			'14' => [ new FN( 12.0003, 4 ), new FN( 11.04 ), 132.48, FN::class, 2 ],//132,483312
		];
	}

	/**
	 * @test
	 * @dataProvider mult_good_numbers
	 * @group        mult_to_good_numbers
	 */
	public function mult_to_good_numbers( $l, $r, $expected, $clazz, $scale ) {
		$res = $this->calc->mult( $l, $r );
		$this->assertInstanceOf( $clazz, $res );
		$this->assertTrue( $res->equals( $expected, $scale ) );

		if ( $res instanceof FloatNumber ) {
			$this->assertEquals( $scale, $res->getScale() );
		}
	}

	public function tva_good_numbers() {
		return [
			[ 100, 0.2, 120.0, 2 ],
			[ 100, 0.2, 120, 2 ],
			[ 3.33, 0, 3.33, 2 ],
			[ 3.33, 1, 6.66, 2 ],
			[ 3.33, 0.2, 4.00, 2 ],
			[ 3.33, 0.2, 4, 2 ],
		];
	}

	/**
	 * @test
	 * @dataProvider tva_good_numbers
	 */
	public function tva_to_good_numbers( $ht, $tva, $expected, $scale ) {
		$res = $this->calc->ttc( new FN( $ht, $scale ), new FN( $tva, $scale ) );

		$this->assertTrue( $res->equals( $expected, $scale ) );
	}

	public function price_good_numbers() {
		return [
			//quantite, prixUnitaireHt, tva, totalHt, totalTtc
			[ 1, 100, 0.20, 100, 120 ],
			[ 0, 100, 0.20, 0, 0 ],
			[ 22, 100, 0.20, 2200, 2640 ],
			[ 1, 3.33, 0.0, 3.33, 3.33 ],
			[ 1, 3.33, 1, 3.33, 6.66 ],
			[ 2, 3.33, 1, 6.66, 13.32 ],
			[ 1, 3.33, 0.2, 3.33, 4 ],
		];
	}

	/**
	 * @test
	 * @dataProvider price_good_numbers
	 */
	public function price_to_good_numbers( $q, $u, $tva, $total_ht, $total_ttc ) {
		$res = $this->calc->price( new IN( $q ), new FN( $u ), new FN( $tva ) );

		$this->assertTrue( $res->getHt()->equals( $total_ht ) );
		$this->assertTrue( $res->getTtc()->equals( $total_ttc ) );
		$this->assertTrue( $res->getQuantite()->equals( $q ) );
		$this->assertTrue( $res->getTva()->equals( $tva ) );
	}


	public function tva_from_int_good_numbers() {
		return [
			[ 0, 0.0, 4 ],
			[ 0, 0, 4 ],
			[ 1, 0.01, 4 ],
			[ 20, 0.2, 4 ],
			[ 100, 1.0, 4 ],
			[ 100, 1, 4 ],
			[ 56.30, 0.5630, 4 ],
		];
	}

	/**
	 * @test
	 * @dataProvider tva_from_int_good_numbers
	 */
	public function tva_from_int_to_good_numbers( $tva, $expected, $scale ) {
		$res = $this->calc->tva( new FN( $tva, $scale ) );

		$this->assertTrue( $res->equals( $expected, $scale ) );
	}

	public function sub_good_numbers() {
		return [
			[ new IN( 1 ), new IN( 2 ), - 1, IN::class, 2 ],
			[ new IN( 3 ), new IN( 2 ), 1, IN::class, 2 ],
			[ new FN( 1 ), new IN( 2 ), - 1.0, FN::class, 2 ],
			[ new IN( 1000 ), new FN( 2.9 ), 997.1, FN::class, 2 ],
			[ new IN( 10 ), new FN( 20.98 ), - 10.98, FN::class, 2 ],
			[ new IN( - 10 ), new IN( 20 ), - 30, IN::class, 2 ],
			[ new IN( 10 ), new IN( - 20 ), 30, IN::class, 2 ],
			[ new IN( - 10 ), new IN( - 20 ), 10, IN::class, 2 ],
			[ new FN( - 10 ), new IN( - 20 ), 10.0, FN::class, 2 ],
			[ new IN( - 10 ), new FN( - 2.87 ), - 7.13, FN::class, 2 ],
			[ new IN( '-10' ), new FN( - 2.87 ), - 7.13, FN::class, 2 ],
			[ new FN( 12.0003, 4 ), new FN( 11.0304, 4 ), 0.9699, FN::class, 4 ],//0.9699
			[ new FN( 15.0003, 4 ), new FN( 11.04 ), 3.96, FN::class, 2 ],//3.9603
			[ new FN( 10 / 3 ), new FN( 12 / 7 ), 1.62, FN::class, 2 ],//1.61904761904762
			[ new FN( 10 / 3, 4 ), new FN( 12 / 7, 4 ), 1.6190, FN::class, 4 ],//1.61904761904762
		];
	}

	/**
	 * @test
	 * @dataProvider sub_good_numbers
	 * @group        sub_to_good_numbers
	 */
	public function sub_to_good_numbers( $l, $r, $expected, $clazz, $scale ) {
		$res = $this->calc->sub( $l, $r );
		$this->assertInstanceOf( $clazz, $res );
		$this->assertTrue( $res->equals( $expected, $scale ) );
		if ( $res instanceof FloatNumber ) {
			$this->assertEquals( $scale, $res->getScale() );
		}
	}
}