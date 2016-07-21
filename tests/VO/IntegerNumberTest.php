<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 13/07/2016
 * Time: 11:36
 */

namespace Test\Enimiste\Math\VO;

use Enimiste\Math\VO\IntegerNumber as IN;
use Test\Enimiste\Math\TestCase;

class IntegerNumberTest extends TestCase {

	public function ints() {
		return [
			[ 1, 1 ],
			[ - 1, - 1 ],
			[ 1.0, 1 ],
			[ - 1.0, - 1 ],
			[ '1', 1 ],
			[ '-1', - 1 ],
			[ '10.9', 10 ],
			[ 10.9, 10 ],
			[ - 10.9, - 10 ],
			[ 0, 0 ],
			[ '0', 0 ],
		];
	}

	public function bad_ints() {
		return [
			[ null ],
			[ '' ],
			[ 'moi' ],
			[ new \stdClass() ],
		];
	}

	/**
	 * @test
	 * @dataProvider ints
	 */
	public function set_get_data( $v, $expected ) {
		$int = new IN( $v );

		$this->assertEquals( $expected, $int->getValue() );
		$this->assertEquals( $v, $int->getOrigin() );
	}

	/**
	 * @test
	 * @dataProvider bad_ints
	 */
	public function set_get_bad_data( $v ) {
		$this->setExpectedException( \RuntimeException::class );
		new IN( $v );
	}

	public function int_equals() {
		return [
			[ new IN( 1 ), 1 ],
			[ new IN( - 1 ), - 1 ],
			[ new IN( - 1 ), new IN( - 1 ) ],
			[ new IN( 0 ), 0 ],
			[ new IN( 11 ), 11 ],
			[ new IN( 11.5 ), 11 ],
			[ new IN( 11.8 ), 11 ],
			[ new IN( 11.8 ), new IN( 11 ) ],
		];
	}

	/**
	 * @test
	 * @dataProvider int_equals
	 */
	public function check_int_equals( IN $i, $expected ) {
		$this->assertTrue( $i->equals( $expected ) );
	}

	public function is_int_values() {
		return [
			[ 1, true ],
			[ '1', true ],
			[ '10', true ],
			[ 10, true ],
			[ 0, true ],
			[ '0', true ],
			[ null, false ],
			[ 9.0, false ],
			[ 0.0, false ],
			[ 0.1, false ],
			[ 12.1, false ],
			[ '', false ],
			[ 'other', false ],
			[ new \stdClass(), false ],
		];
	}

	/**
	 * @test
	 * @dataProvider is_int_values
	 */
	public function is_int_values_test( $i, $expected ) {
		$this->assertEquals( $expected, IN::isInt( $i ) );
	}

	public function gt_values() {
		return [
			[ 1, 2, false ],
			[ 1, new IN( 2 ), false ],
			[ 10, 2, true ],
			[ 0, 0, false ],
			[ 10, 10, false ],
			[ - 21, 1, false ],
			[ - 1, - 3, true ],
			[ - 1, new IN( - 3 ), true ],
			[ - 11, - 3, false ],
			[ 11, - 3, true ],

		];
	}

	/**
	 * @test
	 * @dataProvider gt_values
	 */
	public function check_gt_test( $l, $r, $expected ) {
		$this->assertEquals( $expected, ( new IN( $l ) )->gt( $r ) );
	}

	public function le_values() {
		return [
			[ 1, 2, true ],
			[ 1, new IN( 2 ), true ],
			[ 10, 2, false ],
			[ 0, 0, true ],
			[ 10, 10, true ],
			[ - 21, 1, true ],
			[ - 1, - 3, false ],
			[ - 1, new IN( - 3 ), false ],
			[ - 11, - 3, true ],
			[ 11, - 3, false ],

		];
	}

	/**
	 * @test
	 * @dataProvider le_values
	 */
	public function check_le_test( $l, $r, $expected ) {
		$this->assertEquals( $expected, ( new IN( $l ) )->le( $r ) );
	}

	public function lt_values() {
		return [
			[ 1, 2, true ],
			[ 1, new IN( 2 ), true ],
			[ 10, 2, false ],
			[ 0, 0, false ],
			[ 10, 10, false ],
			[ - 21, 1, true ],
			[ - 1, - 3, false ],
			[ - 1, new IN( - 3 ), false ],
			[ - 11, - 3, true ],
			[ 11, - 3, false ],

		];
	}

	/**
	 * @test
	 * @dataProvider lt_values
	 */
	public function check_lt_test( $l, $r, $expected ) {
		$this->assertEquals( $expected, ( new IN( $l ) )->lt( $r ) );
	}

	public function ge_values() {
		return [
			[ 1, 2, false ],
			[ 1, new IN( 2 ), false ],
			[ 10, 2, true ],
			[ 0, 0, true ],
			[ 10, 10, true ],
			[ - 21, 1, false ],
			[ - 1, - 3, true ],
			[ - 1, new IN( - 3 ), true ],
			[ - 11, - 3, false ],
			[ 11, - 3, true ],

		];
	}

	/**
	 * @test
	 * @dataProvider ge_values
	 */
	public function check_ge_test( $l, $r, $expected ) {
		$this->assertEquals( $expected, ( new IN( $l ) )->ge( $r ) );
	}

}