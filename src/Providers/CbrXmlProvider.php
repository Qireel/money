<?php

namespace Qireel\Money\Providers;

use Qireel\Money\AbstractProvider;
use Qireel\Money\CurrencyRate;
use Qireel\Money\Exceptions\ConnectionException;
use Qireel\Money\Exceptions\ResponseException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;
use DateTime;
use SimpleXMLElement;

class CbrXmlProvider extends AbstractProvider
{
	protected $guzzle;
    protected $url = 'http://www.cbr.ru/scripts/XML_daily_eng.asp';
    protected $urlParams = [];
	protected $target;
	protected $source = 'CBRF XML API';
	
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
		if ($date == null) {
			$date = new DateTime;
		}
		
		$paramDate = $date->format('d/m/Y');
		
		if ($base !== 'RUR') {
			trigger_error('CBRF API only supporting RUR as a base currency', E_USER_WARNING);
			$base = 'RUR';
		}
		
		$this->urlParams['date_req'] = $paramDate;
		$this->target = $target;
		
		$response = $this->query();
		
		return new CurrencyRate($base, $target, $this->source, $date, $response['rate']);
	}
	
	public function query()
	{
		$params = $this->urlParams;
        $url = $this->url . '?' . http_build_query($params);
		
		try {
            $response = $this->guzzle->request('GET', $url);
        } catch (TransferException $e) {
            throw new ConnectionException($e->getMessage());
        }
		
		$result = [];
		
		try {
			$xml = new SimpleXMLElement($response->getBody());
			foreach ($xml as $xmlValute) {
				if ($xmlValute->CharCode == $this->target) {
					$value = (float) str_replace(',', '.', (string)$xmlValute->Value);
					$result = [
						'date' => (string) $xml->attributes()->Date[0],
						'target' => (string) $xmlValute->CharCode,
						'rate' => $value / (int) $xmlValute->Nominal
					];
					break;
				}
			}
		} catch (\Exception $e) {
			throw new ResponseException('Response body is malformed.');
		} finally {
			unset($xml);
		}
		
		return $result;
	}
}