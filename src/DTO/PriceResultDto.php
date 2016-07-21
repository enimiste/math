<?php

namespace Enimiste\Math\DTO;


use Enimiste\Math\VO\FloatNumber;
use Enimiste\Math\VO\IntegerNumber;

class PriceResultDto {

	/** @var  FloatNumber */
	protected $tva;

	/** @var  FloatNumber */
	protected $ht;

	/** @var  FloatNumber */
	protected $ttc;

	/** @var  FloatNumber */
	protected $quantite;

	/**
	 * PriceResultDto constructor.
	 *
	 * @param IntegerNumber $quantite
	 * @param FloatNumber   $ht
	 * @param FloatNumber   $ttc
	 * @param FloatNumber   $tva
	 */
	public function __construct( IntegerNumber $quantite, FloatNumber $ht, FloatNumber $ttc, FloatNumber $tva ) {
		$this->quantite = $quantite;
		$this->ht       = $ht;
		$this->ttc      = $ttc;
		$this->tva      = $tva;
	}

	/**
	 * @return FloatNumber
	 */
	public function getTva() {
		return $this->tva;
	}

	/**
	 * @return FloatNumber
	 */
	public function getHt() {
		return $this->ht;
	}

	/**
	 * @return FloatNumber
	 */
	public function getTtc() {
		return $this->ttc;
	}

	/**
	 * @return IntegerNumber
	 */
	public function getQuantite() {
		return $this->quantite;
	}

}