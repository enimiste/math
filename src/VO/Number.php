<?php
namespace Enimiste\Math\VO;

use Enimiste\Math\VO\Number as N;

/**
 * Class Number
 * @package Enimiste\Math\VO
 */
abstract class Number {

	/** @var  int|float */
	protected $value;

	/** @var string|float|int */
	protected $origin;

	/**
	 * Number constructor.
	 *
	 * @param string|int|float $value
	 */
	public function __construct( $value ) {
		$this->value  = $this->validate( $value );
		$this->origin = $value;
	}

	/**
	 * @return int|float
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @return string|float|int
	 */
	public function getOrigin() {
		return $this->origin;
	}

	/**
	 * @param string|int|float $value
	 *
	 * @return float
	 */
	public function validate( $value ) {
		if ( ! is_numeric( $value ) ) {
			throw new \RuntimeException( 'Invalid numeric value' );
		}

		return $this->_validate( $value );
	}

	/**
	 * @param string|int|float $value
	 *
	 * @return int|float
	 */
	protected abstract function _validate( $value );

	/**
	 * It round the number if the decimal parts is larger than the scale
	 *
	 * @return string
	 */
	public abstract function __toString();

	/**
	 * Check if the given value is equals to this number
	 *
	 * @param string|int|float|Number $other
	 *
	 * @param int                     $scale
	 *
	 * @return bool
	 */
	public function equals( $other, $scale = null ) {
		if ( ! $other instanceof Number ) {
			if ( ! is_numeric( $other ) ) {
				return false;
			} elseif ( IntegerNumber::isInt( $other ) ) {
				$other = new IntegerNumber( $other );
			} else {
				$other = new FloatNumber( $other, is_null( $scale ) ? FloatNumber::PRECISION : $scale );
			}
		}

		return $this->_equals( $other );

	}

	/**
	 * Check if this number is greater than the given value
	 *
	 * @param string|int|float|Number $other
	 *
	 * @param int                     $scale
	 *
	 * @return bool
	 */
	public function gt( $other, $scale = null ) {
		if ( ! $other instanceof Number ) {
			if ( ! is_numeric( $other ) ) {
				return false;
			} elseif ( IntegerNumber::isInt( $other ) ) {
				$other = new IntegerNumber( $other );
			} else {
				$other = new FloatNumber( $other, is_null( $scale ) ? FloatNumber::PRECISION : $scale );
			}
		}

		return $this->_gt( $other );

	}

	/**
	 * Check if this number is less than the given value
	 *
	 * @param string|int|float|Number $other
	 *
	 * @param int                     $scale
	 *
	 * @return bool
	 */
	public function lt( $other, $scale = null ) {
		if ( ! $other instanceof Number ) {
			if ( ! is_numeric( $other ) ) {
				return false;
			} elseif ( IntegerNumber::isInt( $other ) ) {
				$other = new IntegerNumber( $other );
			} else {
				$other = new FloatNumber( $other, is_null( $scale ) ? FloatNumber::PRECISION : $scale );
			}
		}

		return $this->_lt( $other );

	}

	/**
	 * Check if this number is less or equal than the given value
	 *
	 * @param string|int|float|Number $other
	 *
	 * @param int                     $scale
	 *
	 * @return bool
	 */
	public function le( $other, $scale = null ) {
		return ! $this->gt( $other, $scale );
	}

	/**
	 * Check if this number is greater or equal than the given value
	 *
	 * @param string|int|float|Number $other
	 *
	 * @param int                     $scale
	 *
	 * @return bool
	 */
	public function ge( $other, $scale = null ) {
		return ! $this->lt( $other, $scale );
	}

	/**
	 * Check if the given number is equals to this number
	 *
	 * @param N $other
	 *
	 * @return bool
	 */
	protected abstract function _equals( N $other );

	/**
	 * Check if this number is greater than the given value
	 *
	 * @param N $other
	 *
	 * @return mixed
	 */
	protected abstract function _gt( N $other );

	/**
	 * Check if this number is less than the given value
	 *
	 * @param N $other
	 *
	 * @return mixed
	 */
	protected abstract function _lt( N $other );

}