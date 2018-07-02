<?php

use Qireel\Money\Providers\CbrfProvider;
use Qireel\Money\CurrencyRate;
use Mockery;
use PHPUnit\Framework\TestCase;

class CbrfProviderTest extends TestCase
{
    protected $responses = [
        'success' => [
                'status' => 200,
                'meta' => [
                    'sum_deal' => 1,
                    'source' => 'cbrf',
                    'currency_from' => 'USD',
                    'date' => null,
                    'currency_to' => 'RUR',
                    ],
                'data' => [
                    'date' => '2018-07-02 00:09:06',
                    'sum_result' => 62.7565,
                    'rate1' => 62.7565,
                    'rate2' => 0.0159,
                    ]
        ],
        'nodata' => [
            'status' => 200,
            'meta' => [
                'sum_deal' => 1,
                'source' => 'cbrf',
                'currency_from' => 'USD',
                'date' => null,
                'currency_to' => 'RUR',
                ]
        ]
    ];
    
    protected function mock($responseBody)
    {
        $response = Mockery::mock('StdClass');
        $response->shouldReceive('getBody')->once()->andReturn(json_encode($responseBody));
        $client = Mockery::mock('GuzzleHttp\Client');
        $client->shouldReceive('request')->once()->andReturn($response);
        return $client;
    }
    
    public function testIsResultACurrencyRate()
    {
        $cbrf = new CbrfProvider($this->mock($this->responses['success']));
        $result = $cbrf->getRate('EUR', 'RUR');
        $this->assertInstanceOf('Qireel\Money\CurrencyRate', $result);
    }
    
    /**
     * @expectedException Qireel\Money\Exceptions\ConnectionException
     */
    public function testQueryThrowsConnectionExceptionWhenClientThrowsTransferException()
    {
        $client = Mockery::mock('GuzzleHttp\Client');
        $client->shouldReceive('request')->once()->andThrow('GuzzleHttp\Exception\TransferException');
        $cbrf = new CbrfProvider($client);
        $result = $cbrf->getRate('EUR', 'RUR');
    }
    
    /**
     * @expectedException Qireel\Money\Exceptions\ResponseException
     * @expectedExceptionMessage Response body is malformed
     */
    public function testQueryThrowsExceptionOnInvalidResults()
    {
        $cbrf = new CbrfProvider($this->mock($this->responses['nodata']));
        $result = $cbrf->getRate('EUR', 'RUR');
    }
}