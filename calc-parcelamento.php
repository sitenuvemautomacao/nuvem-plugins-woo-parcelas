<?php

/**
 *
 * @since 		1.0.0
 * @author 		Nuvem Automação <site.nuvemautomacao@gmail.com>
 * @version 	1.0.0
 */



if(!defined('ABSPATH')){
	exit;
}

if ( ! class_exists( 'CalcParcelamento' ) ) :

final class CalcParcelamento
{
    /**
     * 0 - Mercado Livre
     * 1 - Marcedo password_get_info
     * 2 - Sem Juros
     */
	public $fator = 'mercado_livre';
	public $valor_minimo = 10;

    public $mercaro_livre = [
        '1' => 0,
        '2' => 0,
        '3' => 0,
        '4' => 7.17,
        '5' => 8.99,
        '6' => 10.49,
        '7' => 11.83,
        '8' => 12.49,
        '9' => 13.21,
        '10' => 14.59,
        '11' => 16.00,
        '12' => 17.41,
    ];

    public $mercaro_pago = [
        '1' => 0,
        '2' => 0,
        '3' => 0,
        '4' => 7.17,
        '5' => 8.99,
        '6' => 10.49,
        '7' => 11.83,
        '8' => 12.49,
        '9' => 13.21,
        '10' => 14.59,
        '11' => 16.00,
        '12' => 17.41,
    ];

    public $sem_juros = [
        '1' => 0,
        '2' => 0,
        '3' => 0,
        '4' => 7.17,
        '5' => 8.99,
        '6' => 10.49,
        '7' => 11.83,
        '8' => 12.49,
        '9' => 13.21,
        '10' => 14.59,
        '11' => 16.00,
        '12' => 17.41,
    ];

    protected $type = 'sem_juros';

    protected static $_instance  = null;

	// $Calc = self::instance();

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function setType($type) {
        $this->type = $type;
		return $this;
    }

    public function setMinimo($minimo) {
        $this->valor_minimo = $minimo;
		return $this;
    }

    public function calc($valor) {
		if($valor == 0) {
			return '';
		}

		$fators = $this->getFator();

		$last = '';
        foreach ($fators as $divisao => $fator) {
            $fator_refinado  = ($fator * 0.01) + 1;
            $total_parcelado = $valor * $fator_refinado;
            $parcela         = $total_parcelado / $divisao;

			if($parcela < $this->valor_minimo) break;
			if($divisao > 6) break;

				$semJurusText = $fator == 0 ? ' SEM JUROS' : '';

            $last =  'em <strong>'. $divisao .'x de '.$this->floatToReal($parcela).'</strong>'.$semJurusText;
        }

		return $last;
    }

	public function parcela_table($valor) {
		if($valor == 0) {
			return '';
		}

		$fators = $this->getFator();

		$html = '';
        foreach ($fators as $divisao => $fator) {
            $fator_refinado  = ($fator * 0.01) + 1;
            $total_parcelado = $valor * $fator_refinado;
            $parcela         = $total_parcelado / $divisao;

			if($parcela < $this->valor_minimo) break;
			$semJurusText = $fator == 0 ? ' SEM JUROS' : '';
            $html .=  '<div class="col-xs-12 col-sm-6"><strong>'. $divisao .'x de '.$this->floatToReal($parcela).'</strong>'.$semJurusText.'</div>';
        }

		$img = '<img src="'.plugins_url('dvd-woo-mercadopago-parcelas/img/bandeiras-cartoes.png').'">';


		return '<div id="table-parcelado"><div class="title">'.$img.' Parcelamento</div><div class="row">'.$html.'</div></div>';
	}

	private function getFator() {
		switch ($this->type) {
            case 'mercaro_livre':
                $fators = $this->mercaro_livre;
                break;
            case 'sem_juros':
                $fators = $this->sem_juros;
                break;
            case 'mercaro_pago':
                $fators = $this->mercaro_pago;
                break;
        }

		return $fators;
	}



	private function floatToReal($float) {
		return 'R$ '. number_format($float, 2, ',', '.');
	}

}

// new CalcParcelamento();



endif;
