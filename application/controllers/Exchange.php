<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exchange extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
        $this->load->view('exchange_view');
	}

    public function getCurrentRates()
    {
        $this->load->helper('currency_api');
        $allRatesEur = getAllRates();
        if(!is_array($allRatesEur)): $errors = 'Error, response code: '.$allRatesEur; else: $errors = ''; endif;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'value' => $allRatesEur['results'],
                'rate' => $allRatesEur['base'],
                'updated' => $allRatesEur['updated'],
                'errors' => $errors
            )));
    }

    public function convert()
    {
        $this->load->helper('currency_api');
        $converted = convertCurrency($_GET['from'], $_GET['to'], $_GET['amount']);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'value' => $converted['result'][$_GET['to']],
                'rate' => $converted['result']['rate']
            )));
    }
}
