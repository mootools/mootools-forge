<?php

/*
 * This file is part of sfXssSafePlugin.
 *
 * (c) Alexandre Mogère
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class sfXssSafe
{
  public static function clean($dirtyHtml)
  {
    if (false === $dirtyHtml || null === $dirtyHtml || 0 === $dirtyHtml)
    {
      return '';
    }
   
    // set error handler to throw exceptions
    set_error_handler(array('sfXssSafe', 'xssSafeErrorHandler'));
  
    static $purifier = false;
    
    if (!$purifier)
    {
      $elements     = array();
      $attributes   = array();
  
      // sets configuration
      $config       = HTMLPurifier_Config::createDefault();
      $definitions  = sfConfig::get('app_sfXssSafePlugin_definition');
    
      if (!empty($definitions))
      {
        foreach ($definitions as $def => $conf)
        {
          if (!empty($conf))
          {
            foreach ($conf as $directive => $values)
            {
              if ($def == 'AutoFormat' && $directive != 'Custom')
              {
                // customizable elements
                if ($directive == 'Element')
                {
                  $elements = $values;
                }
                // customizable attributes
                else if ($directive == 'Attribute')
                {
                  $attributes = $values;
                }
              }
              else
              {
                if ($def == 'AutoFormat' && 
                  $directive == 'Custom' &&
                  !class_exists("HTMLPurifier_Injector_$values"))
                {
                  continue;
                }
                $config->set(sprintf("%s.%s", $def, $directive), $values);
                // $values can be a string or an ArrayList
              }
            }
          }
        }
      }
      
      // deactivated cache for dev environment
      if (in_array(sfConfig::get('sf_environment'), array('dev', 'test')))
      {
        // turns off cache
        $config->set(sprintf("%s.%s", 'Cache', 'DefinitionImpl'), null);
      }
      else
      {
        // sets the cache directory into Symfony cache directory
        $config->set(sprintf("%s.%s", 'Cache', 'SerializerPath'), sfConfig::get('sf_cache_dir'));
      }
      
      //$def = $config->getHTMLDefinition(true);
      if ($def = $config->maybeGetRawHTMLDefinition())
      {
        // adds custom elements
        if (!empty($elements))
        {
          foreach ($elements as $name => $element)
          {
            $name = strtolower($name);
            ${$name} = $def->addElement(
              $name,
              $element['type'],
              $element['contents'],
              $element['attr_includes'],
              $element['attr']
            );
            
            $factory = 'HTMLPurifier_AttrTransform_'.ucfirst($name).'Validator';
            if (class_exists($factory))
            {
              ${$name}->attr_transform_post[] = new $factory();
            }
          }
        }
        
        // adds custom attributs
        if (!empty($attributes))
        {
          foreach ($attributes as $name => $attr)
          {
            $name = isset($attr['tag']) ? strtolower($attr['tag']) : strtolower($name);
            ${$name} = $def->addAttribute(
              $name,
              $attr['attr_name'],
              $attr['def']
            );
          }
        }
      }
      
      $purifier = new HTMLPurifier($config);
    }
    
    $cleanHtml = $purifier->purify($dirtyHtml);
    restore_error_handler();
    
    return $cleanHtml;
  }
  
  /**
   * Error handler.
   *
   * @param mixed Error number
   * @param string Error message
   * @param string Error file
   * @param mixed Error line
   */
  public static function xssSafeErrorHandler($errno, $errstr, $errfile, $errline)
  {
    if (($errno & error_reporting()) == 0)
    {
      return;
    }
  
    throw new sfException(sprintf("[XssSafe class] Error at %s line %s\n\n%s",
      $errfile,
      $errline,
      $errstr
    ));
  }
}