<?php 

namespace App\Helpers;

/**
 * General purpose methods that are needed through the code base but that do
 * really fit elsewhere.
 */
class Utilities {
  /**
   * Masks an email string to hide details
   *
   * @param string
   * @return string
   */
  public static function mask($email) {
    list($account, $domain) = explode("@", $email);
    // Display account as fo******
    $account_mask = substr($account, 0, 2) . str_repeat("*", 6);
    // Display domain as gm****.com
    $domain_components = explode(".", $domain);
    $domain_mask = substr($domain_components[0], 0, 2) . str_repeat("*", 6);
    $tld = array_pop($domain_components);

    return "${account_mask}@${domain_mask}.${tld}";
  }
}

