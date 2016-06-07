<?php 

namespace App\Helpers;

/**
 * General purpose methods that are needed through the code base but that do
 * really fit elsewhere.
 */
class ViewHelper {
  /**
   * Masks an email string to hide details
   *
   * @param string
   * @return string
   */
  public static function mask($email) {
    $email_parts = explode("@", $email);
    if (2 == count($email_parts)) {
      // Display account as fo******
      $account_mask = substr($email_parts[0], 0, 2) . str_repeat("*", 6);
      // Display domain as gm****.com
      $domain_components = explode(".", $email_parts[1]);
      $domain_mask = substr($domain_components[0], 0, 2) . str_repeat("*", 6);
      $tld = array_pop($domain_components);
    
      return "${account_mask}@${domain_mask}.${tld}";
    } else {
      // If not a valid email address just return null
      return null;
    }
  }

  /**
   * Renders a field as a human readable label
   *
   * @param $field
   * @return string
   */
  public static function labelFor($field) {
    return ucwords(strtolower($field));
  }
}

