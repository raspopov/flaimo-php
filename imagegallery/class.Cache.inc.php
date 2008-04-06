<?php
/**
* Caching of generated html/text or images...
*
* User it like this:
* if ($cache->isCached($cache_filename, 14) == true)
*   {
*   echo $cache->returnCache($cache_filename, false, false);
*   }
* else
*   {
*   // create content here and fill $text with it
*   $cache->writeCache($cache_filename, $text, false);
*   }
*
* @access public
* @author Michael Wimmer <flaimo@gmx.net>
* @copyright Michael Wimmer
* @link http://www.flaimo.com/  flaimo.com
* @version 1.000
*/
class Cache
  {
  
  /*-------------------*/
  /* V A R I A B L E S */
  /*-------------------*/

  /**
  * Diectory where cached files are saved
  *
  * @access private
  */ 
  var $cache_dir;
  
  /**
  * Default extention for cache files
  *
  * @access private
  */ 
  var $ext;
 
  /**
  * Default prefix for cache files
  *
  * @access private
  */ 
  var $prefix;
  
  /*-----------------------*/
  /* C O N S T R U C T O R */
  /*-----------------------*/
  
  /**
  * Constructor
  *
  * Only job is to set all the variablesnames
  *
  * @return void
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function Cache($cachedir = '') 
    {
    $this->cache_dir = (string) ((strlen(trim($cachedir)) > 0) ? $cachedir : 'cached');
    $this->ext = (string) 'txt';
    $this->prefix = (string) '';
    if ($this->checkCacheDir() == false)
      {
      die('error creating cache directory');
      } // end if
    } // end constructor
  
  /*-------------------*/    
  /* F U N C T I O N S */
  /*-------------------*/
 
  /**
  * Checks if the cache directory exists, else trys to create it
  *
  * @return boolean
  * @access private
  * @since 1.000 - 2002/10/10   
  */
  function checkCacheDir()
    {
    if (!is_dir($this->cache_dir))
      {
      if (!mkdir($this->cache_dir, 0700))
        {
        return (boolean) false;
        }
      else
        {
        return (boolean) true;
        } // end if
      }
    else
      {
      return (boolean) true;
      } // end if
    } // end function
  
  /**
  * Encodes the temporary filename
  *
  * @return string Encoded string
  * @access private
  * @see decodeFilename()
  * @since 1.000 - 2002/10/10   
  */
  function encodeFilename($filename)
    {
    //return $filename;
    return (string) md5($filename);
    } // end function
  
  /**
  * Decodes the temporary filename
  *
  * @return string Decoded string
  * @access private
  * @see encodeFilename()
  * @since 1.000 - 2002/10/10   
  */
  function decodeFilename($filename)
    {
    return (string) $filename;
    //return (string) str_rot13($filename);
    } // end function
  
  /**
  * Sticks together filename + path
  *
  * @return string
  * @access private
  * @see returnCachePicturename()
  * @since 1.000 - 2002/10/10   
  */
  function returnCacheFilename($filename)
    {
    return (string) $this->cache_dir . '/' . $this->prefix . $this->encodeFilename($filename) . '.' . $this->ext;
    } // end function
  
  /**
  * Sticks together filename + path
  *
  * @return string
  * @access private
  * @see returnCacheFilename()
  * @since 1.000 - 2002/10/10   
  */
  function returnCachePicturename($filename, $filetype)
    {
    return (string) $this->cache_dir . '/' . $this->prefix . $this->encodeFilename($filename) . '.' . $filetype;
    } // end function
  
  /**
  * Checks if a cache file is available and up to date
  *
  * @file string  Name of the cached file
  * @time int  Minimum time until cache file has to be renewed
  * @return boolean
  * @access public
  * @see isPictureCached()
  * @since 1.000 - 2002/10/10   
  */
  function isCached($file, $time = 30)
    {
    $cached = (boolean) false;
    if (file_exists($this->returnCacheFilename($file)) && (time() - filemtime($this->returnCacheFilename($file)) <= $time))
      {
      $cached = (boolean) true;
      } // end if
    return (boolean) $cached;
    } // end function

  /**
  * Checks if a picture is cached and up to date
  *
  * @file string  Name of the cached file
  * @time int  Minimum time until cache file has to be renewed
  * @type string  Type of picture
  * @return boolean
  * @access public
  * @see isCached()
  * @since 1.000 - 2002/10/10   
  */
  function isPictureCached($file, $time = 30, $type = 'png')
    {
    $cached = (boolean) false;
    if (file_exists($this->returnCachePicturename($file, $type)) && (time() - filemtime($this->returnCachePicturename($file, $type)) < $time))
      {
      $cached = (boolean) true;
      } // end if
    return (boolean) $cached;
    } // end function
  
  /**
  * Returns a cached file
  *
  * @file string  Name of the cached file
  * @passthrough boolean  Return cache into a variable or directly output it to the browser
  * @binary boolean  Wether file should be returned in binary mode or not
  * @return mixed
  * @access public
  * @see returnPictureCache()
  * @since 1.000 - 2002/10/10   
  */
  function returnCache($file, $passthrough = false, $binary = false)
    {
    $returntype = (($binary == false) ? 'r' : 'rb');
    if ($passthrough == true)
      {
      return readfile($this->returnCacheFilename($file), $returntype);
      }
    else
      {
      $handle = fopen($this->returnCacheFilename($file), $returntype);
      $cache = fgets($handle);
      fclose($handle);
      return $cache;
      } // end if
    } // end function
 
  /**
  * Returns a cached picture
  *
  * @file string  Name of the cached file
  * @filetype string  Type of picture (jpg|gif|png)
  * @binary boolean  Wether file should be returned in binary mode or not
  * @return string
  * @access public
  * @see returnCache()
  * @since 1.000 - 2002/10/10   
  */
  function returnPictureCache($file, $filetype = 'png', $binary = false)
    {
    $returntype = (($binary == false) ? 'r' : 'rb');
    return readfile($this->returnCachePicturename($file, $filetype), $returntype);
    } // end function
  
  /**
  * Writes a cache file
  *
  * @file string  Name of the cached file
  * @content mixed  Content that should be cached
  * @binary boolean  Wether file should be returned in binary mode or not
  * @return void
  * @access public
  * @see writePictureCache()
  * @since 1.000 - 2002/10/10   
  */
  function writeCache($file, $content, $binary = false)
    {
    $writetype = (($binary == false) ? 'w' : 'wb');
    $handle = fopen($this->returnCacheFilename($file), $writetype);
    fputs($handle, $content);
    fclose($handle);
    } // end function
  
  /**
  * Caches a picture
  *
  * @file string  Name of the cached file
  * @picture binary  The created picture
  * @filetype string  Type of picture (jpg|gif|png)
  * @return void
  * @access public
  * @see writeCache()
  * @since 1.000 - 2002/10/10   
  */
  function writePictureCache($file, $picture, $filetype = 'png')
    {
    $picwritten = (boolean) true;
    if ($filetype == 'jpg')
      {
      imagejpeg($picture, $this->returnCachePicturename($file, $filetype));
      }
    elseif ($filetype == 'gif')
      {
      imagegif($picture, $this->returnCachePicturename($file, $filetype));
      }
    elseif ($filetype == 'png')
      {
      imagepng($picture, $this->returnCachePicturename($file, $filetype));
      }
    else
      {
      $picwritten = (boolean) false;
      } // end if
    return (boolean) $picwritten;
    } // end function
  } // end class Cache
?>