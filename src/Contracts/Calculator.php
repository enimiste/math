<?php

namespace Enimiste\Math\Contracts;

use Enimiste\Math\DTO\PriceResultDto;
use Enimiste\Math\VO\FloatNumber;
use Enimiste\Math\VO\IntegerNumber;
use Enimiste\Math\VO\Number as VONumber;

/**
 * Interface Calculator
 * @package Com\NextGen\Business\Factures\Services
 */
interface Calculator {

	/**
	 * Multiplication
	 *
	 * @param VONumber $l
	 * @param VONumber $r
	 *
	 * @return VONumber
	 */
	public function mult( VONumber $l, VONumber $r );

	/**
	 * Calculate the TTC price from HT and TVA
	 *
	 * @param FloatNumber $ht
	 * @param FloatNumber $tva between 0 and 1
	 *
	 * @return FloatNumber
	 */
	public function ttc( FloatNumber $ht, FloatNumber $tva );

	/**
	 * Add two Numbers
	 *
	 * @param VONumber $l
	 * @param VONumber $r
	 *
	 * @return VONumber
	 */
	public function add( VONumber $l, VONumber $r );

	/**
	 * @param IntegerNumber $quantite
	 * @param FloatNumber   $prixUnitaireHt
	 * @param FloatNumber   $tva
	 *
	 * @return PriceResultDto
	 */
	public function price( IntegerNumber $quantite, FloatNumber $prixUnitaireHt, FloatNumber $tva );

	/**
	 * Build TVA as value betwenn 0 and 1 from a value from 0 to 100
	 *
	 * @param FloatNumber $tva
	 *
	 * @return FloatNumber
	 */
	public function tva( FloatNumber $tva );

	/**
	 * Sub two Numbers
	 * $l - $r
	 *
	 * @param VONumber $l
	 * @param VONumber $r
	 *
	 * @return VONumber
	 */
	public function sub( VONumber $l, VONumber $r );
}