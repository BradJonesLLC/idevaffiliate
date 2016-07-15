<?php

namespace Drupal\idevaffiliate;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Url;
use GuzzleHttp\Client;

/**
 * Class IDevAffiliateRemoteService.
 *
 * @package Drupal\idevaffiliate
 */
class IDevAffiliateRemoteService implements IDevAffiliateRemoteServiceInterface {

  /**
   * The endpoint URL.
   * 
   * @var string
   */
  protected $endpoint = '';

  /**
   * The sale.php protection secret.
   * 
   * Obtain this at General Settings > Advanced Fraud Protection
   * 
   * @var string
   */
  protected $secret = '';

  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $loggerChannel;

  /**
   * Constructor.
   * 
   * @param $configFactory ConfigFactoryInterface The config factory.
   * @param $loggerChannel LoggerChannelInterface Logger channel.
   */
  public function __construct(ConfigFactoryInterface $configFactory, LoggerChannelInterface $loggerChannel) {
    $this->loggerChannel = $loggerChannel;
    $config = $configFactory->get('idevaffiliate.config');
    $this->secret = $config->get('secret');
    $this->endpoint = $config->get('endpoint');
  }

  /**
   * @inheritDoc
   */
  public function sendSale($saleAmount, $ip = NULL, $affiliate = NULL, $orderId = NULL, $commission = NULL) {
    if (!$ip && !$affiliate) {
      throw new \Exception('Either or both of $ip, $affiliate must be specified.');
    }
    $query = [
      'idev_saleamt' => $saleAmount,
      'idev_ordernum' => $orderId,
      'ip_address' => $ip,
      'affiliate_id' => $affiliate,
      'idev_commission' => $commission,
    ];
    return $this->send(array_filter($query));
  }

  /**
   * Send a command to the iDevAffiliate endpoint.
   *
   * @param array $query
   * @param string $command
   * @return bool
   */
  protected function send(array $query, string $command = self::COMMAND_SALE) {
    $query += ['profile' => self::PROFILE_ID];
    $client = new Client();
    $url = Url::fromUri($this->endpoint . $command, ['query' => $query])->toString();
    try {
      $response = $client->get($url);
      if ($response->getStatusCode() !== 200) {
        throw new \Exception($response->getBody()->getContents(), $response->getStatusCode());
      }
    }
    catch (\Throwable $e) {
      $this->loggerChannel->error($e->getMessage());
      return false;
    }
    return true;
  }

}
