<?php

namespace Drupal\idevaffiliate;

/**
 * Interface IDevAffiliateRemoteServiceInterface
 * @package Drupal\idevaffiliate
 */
interface IDevAffiliateRemoteServiceInterface {

  /**
   * The location of the commission generation script.
   */
  const COMMAND_SALE = '/sale.php';

  /**
   * The profile ID to use.
   */
  const PROFILE_ID = 72198;

  /**
   * Send commission data to iDevAffiliate's sale.php endpoint.
   * 
   * The sale amount and EITHER/BOTH of $ip and $affiliate must be specified.
   * 
   * @param int $affiliate The  affiliate ID
   * @param string $saleAmount Sale amount
   * @param mixed|null $orderId The order ID
   * @param string|null $ip IP address of the original order.
   * @param string|null $commission The hard-coded commission amount.
   * @throws \Exception
   * @return bool
   */
  public function sendSale($saleAmount, $ip = NULL, $affiliate = NULL, $orderId = NULL, $commission = NULL);

}
