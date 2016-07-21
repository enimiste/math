<?php
namespace Enimiste\Math\VO;


use Enimiste\Math\VO\Number as N;

class IntegerNumber extends Number {
	public function __construct( $value ) {
		parent::__construct( $value );
	}


	/**
	 * Check whither the input is a valid integer value
	 *
	 * @param string|int|float $other
	 *
	 * @return bool
	 */
	public static function isInt( $other ) {
		return is_numeric( $other ) &&
		       ( is_int( $other ) ||
		         ( is_string( $other ) &&
		           (string) intval( $other ) == $other ) );
	}


	/**
	 * @param string|int|float $value
	 *
	 * @return int
	 */
	protected function _validate( $value ) {
		return is_int( $value ) ? $value : intval( $value );
	}

	/**
	 * It round the number if the decimal parts is larger than the scale
	 *
	 * @return string
	 */
	public function __toString() {
		return sprintf( "%d", $this->value );
	}

	/**
	 * Check if the given value is equals to this number
	 *
	 * @param N $other
	 *
	 * @return bool
	 */
	protected function _equals( N $other ) {
		return $this->getValue() == intval( $other->getValue() );
	}

	/**
	 * Check if this number is greater than the given value
	 *
	 * @param N $other
	 *
	 * @return mixed
	 */
	protected function _gt( N $other ) {
		return $this->getValue() > intval( $other->getValue() );
	}

	/**
	 * Check if this number is less than the given value
	 *
	 * @param N $other
	 *
	 * @return mixed
	 */
	protected function _lt( N $other ) {
		return $this->getValue() < intval( $other->getValue() );
	}
}