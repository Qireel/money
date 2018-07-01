<?php

namespace Qireel\Money\Providers;

use Qireel\Money\AbstractProvider;
use Qireel\Money\CurrencyRate;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;
use DateTime;

class CbrfProvider extends AbstractProvider
{
	protected $guzzle;
    protected $url = 'https://cash.rbc.ru/cash/json/converter_currency_rate/';
    protected $urlParams = [
		'source' => 'cbrf',
		'sum' => 1
	];
	protected $source = 'Cash RBC API';
	
	/**
     * Class constructor.
     *
     * @param \GuzzleHttp\Client $guzzle
     * @return void
     */
    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }
	
	public function getRate($target, $base = 'RUR', DateTime $date = null)
	{
		$paramDate = $date->format('Y-m-d');
		
		$this->urlParams['currency_to'] = $base;
		$this->urlParams['currency_from'] = $target;
		$this->urlParams['date'] = $paramDate;
		
		$response = $this->query();
		
		return new CurrencyRate($base, $target, $this->source, $date, $response['data']['rate1']);
	}
	
	public function query()
	{
		$params = $this->urlParams;
        $url = $this->url . '?' . http_build_query($params);
		
		try {
            $response = $this->guzzle->request('GET', $url);
        } catch (TransferException $e) {
            //throw new ConnectionException($e->getMessage());
        }
		
		$response = json_decode($response->getBody(), true);
		
		if (isset($response['status']) && $response['status'] == 200 && isset($response['data'])) {
            return $response;
        } else {
            //throw new ResponseException('Response body is malformed.');
        }
	}
}