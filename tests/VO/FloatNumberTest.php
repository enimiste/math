<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 13/07/2016
 * Time: 11:36
 */

namespace Test\Enimiste\Math\VO;


use Enimiste\Math\VO\FloatNumber as FN;
use Enimiste\Math\VO\IntegerNumber as IN;
use Test\Enimiste\Math\TestCase;

class FloatNumberTest extends TestCase {

	public function floats() {
		return [
			[ 1, 1.0, '1.00' ],
			[ - 1, - 1.0, '1.00' ],
			[ 1.0, 1.0, '1.00' ],
			[ - 1.0, - 1.0, '-1.00' ],
			[ '1', 1.0, '1.00' ],
			[ '-1', - 1.0, '1.00' ],
			[ '10.9', 10.9, '10.90' ],
			[ 10.9, 10.9, '10.90' ],
			[ - 10.9, - 10.9, '-10.90' ],
			[ 0, 0.0, '0.00' ],
			[ '0', 0.0, '0.00' ],
		];
	}

	public function bad_floats() {
		return [
			[ null ],
			[ '' ],
			[ 'moi' ],
			[ new \stdClass() ],
		];
	}

	/**
	 * @test
	 * @dataProvider floats
	 */
	public function set_get_data( $v, $expected ) {
		$float = new FN( $v );

		$this->assertEquals( $expected, $float->getValue() );
		$this->assertEquals( $v, $float->getOrigin() );
	}

	/**
	 * @test
	 * @dataProvider bad_floats
	 */
	public function set_get_bad_data( $v ) {
		$this->setExpectedException( \RuntimeException::class );
		new FN( $v );
	}

	public function float_equals() {
		return [
			[ new FN( 1 ), 1.0 ],
			[ new FN( - 1 ), - 1.0 ],
			[ new FN( 0 ), 0.0 ],
			[ new FN( 0 ), new FN( 0.0 ) ],
			[ new FN( 0 ), new IN( 0 ) ],
			[ new FN( 11 ), 11.0 ],
			[ new FN( 21.98 ), 21.98 ],
			[ new FN( - 21.98 ), - 21.98 ],
			[ new FN( - 21.98 ), new FN( - 21.98 ) ],
			[ new FN( 10 / 3 ), 3.33 ],
			[ new FN( 1.0 * 2 ), 2.0 ],
			[ new FN( 1.0 / 2 ), .5 ],
			[ new FN( 3.996 ), 4.00 ],
			[ new FN( 3.996 ), new FN( 4.00 ) ],
			[ new FN( 3.996 ), new IN( 4 ) ],
		];
	}

	/**
	 * @test
	 * @dataProvider float_equals
	 */
	public function check_float_equals( FN $i, $expected ) {
		$this->assertTrue( $i->equals( $expected ) );
	}

	public function float_copy() {
		return [
			[ new FN( 1 ), 3 ],
			[ new FN( - 1 ), 3 ],
			[ new FN( 0 ), 3 ],
			[ new FN( 11 ), 3 ],
			[ new FN( 21.98 ), 3 ],
			[ new FN( - 21.98 ), 3 ],
			[ new FN( 10 / 3 ), 3 ],
			[ new FN( 1.0 * 2 ), 3 ],
			[ new FN( 1.0 / 2 ), 3 ],
			[ new FN( 3.996 ), 3 ],
		];
	}

	/**
	 * @test
	 * @dataProvider float_copy
	 */
	public function check_float_copy( FN $i, $scale ) {
		$copy = $i->copy( $scale );
		$this->assertSame( $i->getValue(), $copy->getValue() );
		$this->assertSame( $i->getOrigin(), $copy->getOrigin() );
		$this->assertEquals( $scale, $copy->getScale() );
	}

	/**
	 * @test
	 */
	public function check_idempotent_float_number_conversion() {
		$f0 = new FN( 12.89 );
		$this->assertTrue( $f0->equals( 12.89 ) );

		$f = new FN( $f0->__toString() );
		$this->assertTrue( $f->equals( 12.89 ) );

		foreach ( range( 1, 99 ) as $i ) {
			$f = new FN( $f->__toString() );
			$this->assertTrue( $f->equals( 12.89 ) );
		}

		$this->assertTrue( $f->equals( $f0 ) );
	}

	public function gt_values() {
		return [
			[ 1.0, 2.0, false, 2 ],
			[ 1.0, new FN( 2.0 ), false, 2 ],
			[ 10.0, 2.0, true, 2 ],
			[ 10.23, 2.2, true, 2 ],
			[ 10.23, 10.22, true, 2 ],
			[ 0.0, 0.0, false, 2 ],
			[ 10.0, 10.0, false, 2 ],
			[ 10.1, 10.1, false, 2 ],
			[ - 21.0, 1.1, false, 2 ],
			[ - 1.0, - 3.0, true, 2 ],
			[ - 1.0, new IN( - 3.0 ), true, 2 ],
			[ - 11.0, - 3.0, false, 2 ],
			[ 11.0, - 3.0, true, 2 ],
			[ 11.1246, 11.1245, true, 4 ],
			[ 11.1246, 11.1245, false, 2 ],

		];
	}

	/**
	 * @test
	 * @dataProvider gt_values
	 */
	public function check_gt_test( $l, $r, $expected, $scale ) {
		$this->assertEquals( $expected, ( new FN( $l ) )->gt( $r, $scale ) );
	}

	public function le_values() {
		return [
			[ 1.0, 2.0, true, 2 ],
			[ 1.0, new FN( 2.0 ), true, 2 ],
			[ 10.0, 2.0, false, 2 ],
			[ 10.23, 2.2, false, 2 ],
			[ 10.23, 10.22, false, 2 ],
			[ 0.0, 0.0, true, 2 ],
			[ 10.0, 10.0, true, 2 ],
			[ 10.1, 10.1, true, 2 ],
			[ - 21.0, 1.1, true, 2 ],
			[ - 1.0, - 3.0, false, 2 ],
			[ - 1.0, new IN( - 3.0 ), false, 2 ],
			[ - 11.0, - 3.0, true, 2 ],
			[ 11.0, - 3.0, false, 2 ],
			[ 11.1246, 11.1245, false, 4 ],
			[ 11.1246, 11.1245, true, 2 ],

		];
	}

	/**
	 * @test
	 * @dataProvider le_values
	 */
	public function check_le_test( $l, $r, $expected, $scale ) {
		$this->assertEquals( $expected, ( new FN( $l ) )->le( $r, $scale ) );
	}

	public function lt_values() {
		return [
			[ 1.0, 2.0, true, 2 ],
			[ 1.0, new FN( 2.0 ), true, 2 ],
			[ 10.0, 2.0, false, 2 ],
			[ 10.23, 2.2, false, 2 ],
			[ 10.23, 10.22, false, 2 ],
			[ 0.0, 0.0, false, 2 ],
			[ 10.0, 10.0, false, 2 ],
			[ 10.1, 10.1, false, 2 ],
			[ - 21.0, 1.1, true, 2 ],
			[ - 1.0, - 3.0, false, 2 ],
			[ - 1.0, new IN( - 3.0 ), false, 2 ],
			[ - 11.0, - 3.0, true, 2 ],
			[ 11.0, - 3.0, false, 2 ],
			[ 11.1246, 11.1245, false, 4 ],
			[ 11.1246, 11.1245, false, 2 ],

		];
	}

	/**
	 * @test
	 * @dataProvider lt_values
	 */
	public function check_lt_test( $l, $r, $expected, $scale ) {
		$this->assertEquals( $expected, ( new FN( $l ) )->lt( $r, $scale ) );
	}

	public function ge_values() {
		return [
			[ 1.0, 2.0, false, 2 ],
			[ 1.0, new FN( 2.0 ), false, 2 ],
			[ 10.0, 2.0, true, 2 ],
			[ 10.23, 2.2, true, 2 ],
			[ 10.23, 10.22, true, 2 ],
			[ 0.0, 0.0, true, 2 ],
			[ 10.0, 10.0, true, 2 ],
			[ 10.1, 10.1, true, 2 ],
			[ - 21.0, 1.1, false, 2 ],
			[ - 1.0, - 3.0, true, 2 ],
			[ - 1.0, new IN( - 3.0 ), true, 2 ],
			[ - 11.0, - 3.0, false, 2 ],
			[ 11.0, - 3.0, true, 2 ],
			[ 11.1246, 11.1245, true, 4 ],
			[ 11.1246, 11.1245, true, 2 ],

		];
	}

	/**
	 * @test
	 * @dataProvider ge_values
	 */
	public function check_ge_test( $l, $r, $expected, $scale ) {
		$this->assertEquals( $expected, ( new FN( $l ) )->ge( $r, $scale ) );
	}
}