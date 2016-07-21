<?php
namespace Enimiste\Math\BcMath;


use Enimiste\Math\Contracts\Calculator;
use Enimiste\Math\DTO\PriceResultDto;
use Enimiste\Math\VO\FloatNumber;
use Enimiste\Math\VO\IntegerNumber;
use Enimiste\Math\VO\Number as VONumber;

class BcMathCalculator implements Calculator {

	/**
	 * Multiplication
	 *
	 * @param VONumber $l
	 * @param VONumber $r
	 *
	 * @return VONumber
	 */
	public function mult( VONumber $l, VONumber $r ) {
		if ( $l instanceof IntegerNumber && $r instanceof IntegerNumber ) {
			return new IntegerNumber( bcmul( $l->__toString(), $r->__toString() ) );
		} else {
			$scale = FloatNumber::getScales( $l, $r );

			$res = new FloatNumber( bcmul( $l->__toString(), $r->__toString(), $scale[1] ), $scale[0] );

			return $res;
		}
	}

	/**
	 * Calculate the TTC price from HT and TVA
	 *
	 * @param FloatNumber $ht
	 * @param FloatNumber $tva between 0 and 1
	 *
	 * @return FloatNumber
	 */
	public function ttc( FloatNumber $ht, FloatNumber $tva ) {
		if ( $tva->getValue() < 0 || $tva->getValue() > 100 ) {
			throw new \RuntimeException( sprintf( 'Tva %.2f should be between 0 and 1', $tva->getValue() ) );
		}

		if ( $ht->getScale() < 4 && $tva->getScale() < 4 ) {
			$scale = 4;//default
		} else {
			$scale = FloatNumber::getScales( $ht, $tva )[1];//max
		}

		$cht  = $ht->copy( $scale );
		$ctva = $tva->copy( $scale );

		/** @var FloatNumber $res */
		$res = $this->add( $cht, $this->mult( $cht, $ctva ) );

		return $res->copy( FloatNumber::getScales( $ht, $tva )[0] );
	}

	/**
	 * Add to Numbers
	 *
	 * @param VONumber $l
	 * @param VONumber $r
	 *
	 * @return VONumber
	 */
	public function add( VONumber $l, VONumber $r ) {
		if ( $l instanceof IntegerNumber && $r instanceof IntegerNumber ) {
			return new IntegerNumber( bcadd( $l->__toString(), $r->__toString() ) );
		} else {
			$scale = FloatNumber::getScales( $l, $r );

			$res = new FloatNumber( bcadd( $l->__toString(), $r->__toString(), $scale[1] ), $scale[0] );

			return $res;
		}
	}

	/**
	 * @param IntegerNumber $quantite
	 * @param FloatNumber   $prixUnitaireHt
	 * @param FloatNumber   $tva
	 *
	 * @return PriceResultDto
	 */
	public function price( IntegerNumber $quantite, FloatNumber $prixUnitaireHt, FloatNumber $tva ) {
		$ht = new FloatNumber( $this->mult( $quantite, $prixUnitaireHt )->__toString() );

		return new PriceResultDto( $quantite, $ht, $this->ttc( $ht, $tva ), $tva );
	}

	/**
	 * Build TVA as value betwenn 0 and 1 from a value from 0 to 100
	 *
	 * @param FloatNumber $tva
	 *
	 * @return FloatNumber
	 */
	public function tva( FloatNumber $tva ) {
		$tva = $tva->copy( 4 );

		return new FloatNumber( $tva->getValue() / 100, 4 );
	}

	/**
	 * Sub two Numbers
	 * $l - $r
	 *
	 * @param VONumber $l
	 * @param VONumber $r
	 *
	 * @return VONumber
	 */
	public function sub( VONumber $l, VONumber $r ) {
		if ( $l instanceof IntegerNumber && $r instanceof IntegerNumber ) {
			return new IntegerNumber( bcsub( $l->__toString(), $r->__toString() ) );
		} else {
			$scale = FloatNumber::getScales( $l, $r );

			$res = new FloatNumber( bcsub( $l->__toString(), $r->__toString(), $scale[1] ), $scale[0] );

			return $res;
		}
	}
}