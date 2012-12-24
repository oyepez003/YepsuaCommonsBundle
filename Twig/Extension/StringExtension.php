<?php

namespace Yepsua\CommonsBundle\Twig\Extension;

class StringExtension extends \Twig_Extension {
  
  /**
   * {@inheritdoc}
   */
  public function getFilters()
  {
    return array(
        'raw_url_decode' => new \Twig_Filter_Method($this, 'rawUrlDecode')
    );
  }
  
  /**
   * URL Decode a string
   *
   * @param string $url
   *
   * @return string The decoded URL
   */
  public function rawUrlDecode( $url )
  {
    return rawurldecode( $url );
  }

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName() {
    return 'commons.string';
  }
  
}