<?php
namespace Enimiste\Math\VO;

use Enimiste\Math\VO\Number as N;

/**
 * Class FloatNumber
 * @package Enimiste\Math\VO
 */
class FloatNumber extends N {

	const PRECISION = 2;

	/** @var  int */
	protected $scale;

	/**
	 * FloatNumber constructor.
	 *
	 * @param float|int|string $value
	 * @param int              $scale
	 */
	public function __construct( $value, $scale = 2 ) {
		parent::__construct( $value );

		$this->setScale( $scale );
	}

	/**
	 * Copy this number to a new instance
	 *
	 * @param int $scale new scale
	 *
	 * @return FloatNumber
	 */
	public function copy( $scale = 2 ) {
		$f         = new FloatNumber( 0, $scale );
		$f->value  = $this->value;
		$f->origin = $this->origin;

		return $f;
	}

	/**
	 * @return int
	 */
	public function getScale() {
		return $this->scale;
	}

	/**
	 * @param int $scale
	 */
	protected function setScale( $scale ) {
		$this->scale = intval( $scale );
		if ( $this->scale < 0 || $this->scale > 10 ) {
			throw new \RuntimeException( 'Scale should be greater or equal to zero and less than 10' );
		}
	}


	/**
	 * @param string|int|float $value
	 *
	 * @return float
	 */
	protected function _validate( $value ) {
		return is_float( $value ) ? $value : floatval( $value );
	}

	/**
	 * It round the number if the decimal parts is larger than the scale
	 *
	 * @return string
	 */
	public function __toString() {
		return number_format( $this->value, $this->getScale(), '.', '' );
	}

	/**
	 * Check if the given value is equals to this number
	 *
	 * @param N $other
	 *
	 * @return bool
	 */
	protected function _equals( N $other ) {
		if ( $other instanceof FloatNumber ) {
			return $this->__toString() == $other->__toString();
		} else {
			return $this->__toString() == ( new FloatNumber( $other->__toString() ) )->__toString();
		}
	}

	/**
	 * Check if this number is greater than the given value
	 *
	 * @param N $other
	 *
	 * @return mixed
	 */
	protected function _gt( N $other ) {
		if ( ! $other instanceof FloatNumber ) {
			$other = new FloatNumber( $other->__toString() );
		}

		$scales = self::getScales( $this, $other );

		$l = $this->copy( $scales[1] );
		$r = $other->copy( $scales[1] );

		$ls = preg_replace( "#[^0-9\-]#", '', $l->__toString() );
		$rs = preg_replace( "#[^0-9\-]#", '', $r->__toString() );

		return ( new IntegerNumber( $ls ) )->gt( new IntegerNumber( $rs ) );
	}

	/**
	 * Returns the (min, max) of the two scales or zero
	 *
	 * @param N $l
	 * @param N $r
	 *
	 * @return array [min, max]
	 */
	public static function getScales( N $l, N $r ) {
		$scales = [ ];
		if ( $l instanceof FloatNumber ) {
			$scales[] = $l->getScale();
		}
		if ( $r instanceof FloatNumber ) {
			$scales[] = $r->getScale();
		}

		if ( count( $scales ) == 1 ) {
			$scales[] = $scales[0];
		}

		return [ min( $scales ), max( $scales ) ];
	}

	/**
	 * Check if this number is less than the given value
	 *
	 * @param N $other
	 *
	 * @return mixed
	 */
	protected function _lt( N $other ) {
		if ( ! $other instanceof FloatNumber ) {
			$other = new FloatNumber( $other->__toString() );
		}

		$scales = self::getScales( $this, $other );

		$l = $this->copy( $scales[1] );
		$r = $other->copy( $scales[1] );

		$ls = preg_replace( "#[^0-9\-]#", '', $l->__toString() );
		$rs = preg_replace( "#[^0-9\-]#", '', $r->__toString() );

		return ( new IntegerNumber( $ls ) )->lt( new IntegerNumber( $rs ) );
	}
}