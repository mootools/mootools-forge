<?php
/**
 * This file is derived from PHP API of the sfSphinx package.
 * (c) 2001-2010 Andrew Aksyonoff
 * (c) 2007      Rick Olson <rick@napalmriot.com>
 * (c) 2008-2010 Massimiliano Arione <garakkio@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSphinxClient.
 *
 * based on php api class of Sphinx project
 *
 * @package    sfSphinxPlugin
 * @author     Massimiliano Arione <garakkio@gmail.com>
 */
class sfSphinxClient
{

  // known searchd commands
  const SEARCHD_COMMAND_SEARCH     = 0;
  const SEARCHD_COMMAND_EXCERPT    = 1;
  const SEARCHD_COMMAND_UPDATE     = 2;
  const SEARCHD_COMMAND_KEYWORDS   = 3;
  const SEARCHD_COMMAND_PERSIST    = 4;
  const SEARCHD_COMMAND_STATUS     = 5;
  const SEARCHD_COMMAND_QUERY      = 6;
  const SEARCHD_COMMAND_FLUSHATTRS = 7;

  // current client-side command implementation versions
  const VER_COMMAND_SEARCH     = 0x117;
  const VER_COMMAND_EXCERPT    = 0x102;
  const VER_COMMAND_UPDATE     = 0x102;
  const VER_COMMAND_KEYWORDS   = 0x100;
  const VER_COMMAND_STATUS     = 0x100;
  const VER_COMMAND_QUERY      = 0x100;
  const VER_COMMAND_FLUSHATTRS = 0x100;

  // known searchd status codes
  const SEARCHD_OK      = 0;
  const SEARCHD_ERROR   = 1;
  const SEARCHD_RETRY   = 2;
  const SEARCHD_WARNING = 3;

  // known match modes
  const SPH_MATCH_ALL       = 0;
  const SPH_MATCH_ANY       = 1;
  const SPH_MATCH_PHRASE    = 2;
  const SPH_MATCH_BOOLEAN   = 3;
  const SPH_MATCH_EXTENDED  = 4;
  const SPH_MATCH_FULLSCAN  = 5;
  const SPH_MATCH_EXTENDED2 = 6;  // extended engine V2 (temporary)

  // known ranking modes (ext2 only)
  const SPH_RANK_PROXIMITY_BM25 = 0; // default mode, phrase proximity major factor and BM25 minor one
  const SPH_RANK_BM25           = 1; // statistical mode, BM25 ranking only (faster but worse quality)
  const SPH_RANK_NONE           = 2; // no ranking, all matches get a weight of 1
  const SPH_RANK_WORDCOUNT      = 3; // simple word-count weighting, rank is a weighted sum of
                                     //  per-field keyword occurence counts
  const SPH_RANK_PROXIMITY      = 4;
  const SPH_RANK_MATCHANY       = 5;
  const SPH_RANK_FIELDMASK      = 6;
  const SPH_RANK_SPH04          = 7;
  const SPH_RANK_TOTAL          = 8;

  // known sort modes
  const SPH_SORT_RELEVANCE     = 0;
  const SPH_SORT_ATTR_DESC     = 1;
  const SPH_SORT_ATTR_ASC      = 2;
  const SPH_SORT_TIME_SEGMENTS = 3;
  const SPH_SORT_EXTENDED      = 4;
  const SPH_SORT_EXPR          = 5;

  // known filter types
  const SPH_FILTER_VALUES     = 0;
  const SPH_FILTER_RANGE      = 1;
  const SPH_FILTER_FLOATRANGE = 2;

  // known attribute types
  const SPH_ATTR_INTEGER   = 1;
  const SPH_ATTR_TIMESTAMP = 2;
  const SPH_ATTR_ORDINAL   = 3;
  const SPH_ATTR_BOOL      = 4;
  const SPH_ATTR_FLOAT     = 5;
  const SPH_ATTR_BIGINT    = 6;
  const SPH_ATTR_STRING    = 7;
  const SPH_ATTR_MULTI     = 0x40000000;

  // known grouping functions
  const SPH_GROUPBY_DAY      = 0;
  const SPH_GROUPBY_WEEK     = 1;
  const SPH_GROUPBY_MONTH    = 2;
  const SPH_GROUPBY_YEAR     = 3;
  const SPH_GROUPBY_ATTR     = 4;
  const SPH_GROUPBY_ATTRPAIR = 5;

  protected
    $host,          // searchd host (default is 'localhost')
    $port,          // searchd port (default is 3312)
    $offset,        // how many records to seek from result-set start (default is 0)
    $limit,         // how many records to return from result-set (default is 20)
    $mode,          // query matching mode (default is self::SPH_MATCH_ALL)
    $weights,       // per-field weights (default is 1 for all fields)
    $sort,          // match sorting mode (default is self::SPH_SORT_RELEVANCE)
    $sortby,        // attribute to sort by (defualt is '')
    $min_id,        // min ID to match (default is 0)
    $max_id,        // max ID to match (default is UINT_MAX)
    $filters,       // search filters
    $min,           // attribute name to min-value hash (for range filters)
    $max,           // attribute name to max-value hash (for range filters)
    $filter,        // attribute name to values set hash (for values-set filters)
    $groupby,       // group-by attribute name (default is self::SPH_GROUPBY_DAY)
    $groupfunc,     // function to pre-process group-by attribute value with
    $groupsort,     // group-by sorting clause (to sort groups in result set with)
    $groupdistinct, // group-by count-distinct attribute
    $maxmatches,    // max matches to retrieve (default is 1000)
    $cutoff,        // cutoff to stop searching at (default is 0)
    $retrycount,    // distributed retries count
    $retrydelay,    // distributed retries delay
    $anchor,        // geographical anchor point
    $indexweights,  // per-index weights
    $ranker,        // ranking mode (default is self::SPH_RANK_PROXIMITY_BM25)
    $maxquerytime,  // max query time, milliseconds (default is 0, do not limit)
    $fieldweights,  // per-field-name weights
    $overrides,     // per-query attribute values overrides
    $select,        // select-list (attributes or expressions, with optional aliases)
    $mbenc,         // stored mbstring encoding
    $arrayresult,   // whether $result['matches'] should be a hash or an array
    $timeout;       // connect timeout
  private
    $reqs,          // requests array for multi-query
    $error,         // last error message
    $warning,       // last warning message
    $conerror,      // connection error vs remote error flag
    $res;           // result from RunQueries()

  /**
   * create a new client object, filling defaults for options not passed
   * @param array $options
   */
  public function __construct($options)
  {
    $default_options = array(
      'host'          => 'localhost',
      'port'          => 3312,
      'path'          => false,
      'socket'        => false,
      'offset'        => 0,
      'limit'         => 20,
      'mode'          => self::SPH_MATCH_ALL,
      'weights'       => array(),
      'sort'          => self::SPH_SORT_RELEVANCE,
      'sortby'        => '',
      'min_id'        => 0,
      'max_id'        => 0xFFFFFFFF,
      'filters'       => array(),
      'groupby'       => '',
      'groupfunc'     => self::SPH_GROUPBY_DAY,
      'groupsort'     => '@group desc',
      'groupdistinct' => '',
      'maxmatches'    => 1000,
      'cutoff'        => 0,
      'retrycount'    => 0,
      'retrydelay'    => 0,
      'anchor'        => array(),
      'indexweights'  => array(),
      'ranker'        => self::SPH_RANK_PROXIMITY_BM25,
      'maxquerytime'  => 0,
      'fieldweights'  => array(),
      'overrides'     => array(),
      'select'        => '*',
      'mbenc'         => '',
      'arrayresult'   => true,
      'timeout'       => 0,
    );
    $available_options = array_keys($default_options);
    $new_options = array_merge($default_options, $options);
    foreach ($new_options as $k => $v)
    {
      if (in_array($k, $available_options))
      {
        $this->$k = $v;
      }
    }
    $this->reqs     = array();
    $this->error    = '';
    $this->warning  = '';
    $this->conerror = false;
    $this->res      = false;
  }

  /**
   * destructor
   */
  public function __destruct()
  {
    if ($this->socket !== false)
    {
      fclose($this->socket);
    }
  }

  /**
   * pack 64-bit signed
   * @param  mixed  $v integer/float
   * @return string
   */
  private function sphPackI64($v)
  {
    // x64
    if (PHP_INT_SIZE >= 8)
    {
      $v = (int) $v;
      return pack('NN', $v >> 32, $v&0xFFFFFFFF);
    }

    // x32, int
    if (is_int($v))
    {
      return pack('NN', $v < 0 ? -1 : 0, $v);
    }

    // x32, bcmath
    if (function_exists('bcmul'))
    {
      if (bccomp($v, 0) == -1)
      {
        $v = bcadd('18446744073709551616', $v);
      }
      $h = bcdiv($v, '4294967296', 0);
      $l = bcmod($v, '4294967296');
      // conversion to float is intentional; int would lose 31st bit
      return pack('NN', (float) $h, (float) $l);
    }

    // x32, no-bcmath
    $p = max(0, strlen($v) - 13);
    $lo = abs((float) substr($v, $p));
    $hi = abs((float) substr($v, 0, $p));

    $m = $lo + $hi * 1316134912.0; // (10 ^ 13) % (1 << 32) = 1316134912
    $q = floor($m / 4294967296.0);
    $l = $m - ($q * 4294967296.0);
    $h = $hi * 2328.0 + $q; // (10 ^ 13) / (1 << 32) = 2328

    if ($v < 0)
    {
      if ($l == 0)
      {
        $h = 4294967296.0 - $h;
      }
      else
      {
        $h = 4294967295.0 - $h;
        $l = 4294967296.0 - $l;
      }
    }

    return pack('NN', $h, $l);
  }

  /**
   * pack 64-bit unsigned
   * @param  mixed  $v integer/float
   * @return string
   */
  public function sphPackU64($v)
  {
    // x64
    if (PHP_INT_SIZE >= 8)
    {
      // x64, int
      if (is_int($v))
      {
        return pack('NN', $v >> 32, $v&0xFFFFFFFF);
      }

      // x64, bcmath
      if (function_exists('bcmul'))
      {
        $h = bcdiv($v, 4294967296, 0);
        $l = bcmod($v, 4294967296);
        return pack('NN', $h, $l);
      }

      // x64, no-bcmath
      $p = max(0, strlen($v) - 13);
      $lo = (int) substr($v, $p);
      $hi = (int) substr($v, 0, $p);

      $m = $lo + $hi * 1316134912;
      $l = $m % 4294967296;
      $h = $hi * 2328 + (int) ($m / 4294967296);

      return pack('NN', $h, $l);
    }

    // x32, int
    if (is_int($v))
    {
      return pack('NN', 0, $v);
    }

    // x32, bcmath
    if (function_exists('bcmul'))
    {
      $h = bcdiv($v, '4294967296', 0);
      $l = bcmod($v, '4294967296');
      // conversion to float is intentional; int would lose 31st bit
      return pack('NN', (float) $h, (float) $l);
    }

    // x32, no-bcmath
    $p = max(0, strlen($v) - 13);
    $lo = (float) substr($v, $p);
    $hi = (float) substr($v, 0, $p);

    $m = $lo + $hi * 1316134912.0;
    $q = floor($m / 4294967296.0);
    $l = $m - ($q * 4294967296.0);
    $h = $hi * 2328.0 + $q;

    return pack('NN', $h, $l);
  }

  /**
   * unpack 64-bit unsigned
   * @param  mixed  $v integer/float
   * @return string
   */
  private function sphUnpackU64($v)
  {
    list($hi, $lo) = array_values(unpack('N*N*', $v));

    if (PHP_INT_SIZE >= 8)
    {
      if ($hi < 0)
      {
        $hi += (1 << 32); // because php 5.2.2 to 5.2.5 is totally fucked up again
      }
      if ($lo < 0)
      {
        $lo += (1 << 32);
      }

      // x64, int
      if ($hi <= 2147483647)
      {
        return ($hi << 32) + $lo;
      }

      // x64, bcmath
      if (function_exists('bcmul'))
      {
        return bcadd($lo, bcmul($hi, '4294967296'));
      }

      // x64, no-bcmath
      $C = 100000;
      $h = ((int) ($hi / $C) << 32) + (int)($lo / $C);
      $l = (($hi % $C) << 32) + ($lo % $C);
      if ($l > $C)
      {
        $h += (int) ($l / $C);
        $l  = $l % $C;
      }

      if ($h == 0)
      {
        return $l;
      }
      return sprintf('%d%05d', $h, $l);
    }

    // x32, int
    if ($hi == 0)
    {
      if ($lo > 0)
      {
        return $lo;
      }
      return sprintf('%u', $lo);
    }

    $hi = sprintf('%u', $hi);
    $lo = sprintf('%u', $lo);

    // x32, bcmath
    if (function_exists('bcmul'))
    {
      return bcadd($lo, bcmul($hi, '4294967296'));
    }

    // x32, no-bcmath
    $hi = (float) $hi;
    $lo = (float) $lo;

    $q = floor($hi / 10000000.0);
    $r = $hi - $q * 10000000.0;
    $m = $lo + $r * 4967296.0;
    $mq = floor($m / 10000000.0);
    $l = $m - $mq * 10000000.0;
    $h = $q * 4294967296.0 + $r * 429.0 + $mq;

    $h = sprintf('%.0f', $h);
    $l = sprintf('%07.0f', $l);
    if ($h == '0')
    {
      return sprintf('%.0f', (float) $l);
    }

    return $h . $l;
  }

  /**
   * unpack 64-bit signed
   * @param  mixed  $v integer/float
   * @return string
   */
  private function sphUnpackI64($v)
  {
    list($hi, $lo) = array_values(unpack('N*N*', $v));

    // x64
    if (PHP_INT_SIZE >= 8)
    {
      if ($hi < 0)
      {
        $hi += (1 << 32); // because php 5.2.2 to 5.2.5 is totally fucked up again
      }
      if ($lo < 0)
      {
        $lo += (1 << 32);
      }

      return ($hi << 32) + $lo;
    }

    // x32, int
    if ($hi == 0)
    {
      if ($lo > 0)
      {
        return $lo;
      }
      return sprintf('%u', $lo);
    }
    // x32, int
    elseif ($hi == -1)
    {
      if ($lo < 0)
      {
        return $lo;
      }
      return sprintf('%.0f', $lo - 4294967296.0);
    }

    $neg = '';
    $c = 0;
    if ($hi < 0)
    {
      $hi = ~$hi;
      $lo = ~$lo;
      $c = 1;
      $neg = '-';
    }

    $hi = sprintf('%u', $hi);
    $lo = sprintf('%u', $lo);

    // x32, bcmath
    if (function_exists('bcmul'))
    {
      return $neg . bcadd(bcadd($lo, bcmul($hi, '4294967296')), $c);
    }

    // x32, no-bcmath
    $hi = (float) $hi;
    $lo = (float) $lo;

    $q = floor($hi / 10000000.0);
    $r = $hi - $q * 10000000.0;
    $m = $lo + $r * 4967296.0;
    $mq = floor($m / 10000000.0);
    $l = $m - $mq * 10000000.0 + $c;
    $h = $q * 4294967296.0 + $r * 429.0 + $mq;

    $h = sprintf('%.0f', $h);
    $l = sprintf('%07.0f', $l);
    if ($h == '0')
    {
      return $neg . sprintf('%.0f', (float) $l);
    }

    return $neg . $h . $l;
  }

  /**
   * helper to pack floats in network byte order
   * @param  float   $f
   * @return integer
   */
  private function packFloat($f)
  {
    $t1 = pack('f', $f); // machine order
    list(, $t2) = unpack('L*', $t1); // int in machine order
    return pack('N', $t2);
  }

  /**
   * enter mbstring workaround mode
   */
  private function MBPush()
  {
    $this->mbenc = '';
    if (ini_get('mbstring.func_overload') & 2)
    {
      $this->mbenc = mb_internal_encoding();
      mb_internal_encoding('latin1');
    }
  }

  /**
   * leave mbstring workaround mode
   */
  private function MBPop()
  {
    if ($this->mbenc)
    {
      mb_internal_encoding($this->mbenc);
    }
  }

  /**
   * escape a string
   * @param  string $string
   * @return string
   */
  private function EscapeString($string)
  {
    $from = array('\\', '(',')','|','-','!','@','~','"','&', '/', '^', '$', '=');
    $to   = array('\\\\', '\(','\)','\|','\-','\!','\@','\~','\"', '\&', '\/', '\^', '\$', '\=');

    return str_replace($from, $to, $string);
  }

  /**
   * check if there's an error or not
   * @return string
   */
  public function isError()
  {
    return !empty($this->error);
  }

  /**
   * get last error message
   * @return string
   */
  public function GetLastError()
  {
    return $this->error;
  }

  /**
   * get last warning message
   * @return string
   */
  public function GetLastWarning()
  {
    return $this->warning;
  }

  /**
   * get last error flag
   * (to tell network connection errors from searchd errors or broken responses)
   * @return mixed
   */
  public function isConnectError()
  {
    return $this->conerror;
  }

  /**
   * set searchd server
   * @param string  $host
   * @param integer $port
   */
  public function SetServer($host, $port)
  {
    $this->host = $host;
    if ($host[0] == '/')
    {
      $this->path = 'unix://' . $host;
      return;
    }
    if (substr($host, 0, 7) == 'unix://')
    {
      $this->path = $host;
      return;
    }
    $this->port = $port;
    $this->path = '';
  }

  /**
   * set server connection timeout (0 to remove)
   * @param integer $timeout
   */
  public function SetConnectTimeout($timeout)
  {
    $this->timeout = $timeout;
  }

  /**
   * @param  resource $handle
   * @param  string   $data
   * @param  integer  $length
   * @return boolean
   */
  protected function Send($handle, $data, $length)
  {
    if (feof($handle) || fwrite($handle, $data, $length) !== $length)
    {
      $this->error = 'connection unexpectedly closed (timed out?)';
      $this->connerror = true;
      return false;
    }

    return true;
  }

  /**
   * connect to searchd server
   * @return resource
   * @throws Exception
   */
  protected function Connect()
  {
    if ($this->socket !== false)
    {
      return $this->socket;
    }

    $errno = 0;
    $errstr = '';
    $this->connerror = false;

    if ($this->path)
    {
      $host = $this->path;
      $port = 0;
    }
    else
    {
      $host = $this->host;
      $port = $this->port;
    }

    if ($this->timeout <= 0)
    {
      $fp = @fsockopen($host, $port, $errno, $errstr);
    }
    else
    {
      $fp = @fsockopen($host, $port, $errno, $errstr, $this->timeout);
    }

    if (!$fp)
    {
      if ($this->path)
      {
        $location = $this->path;
      }
      else
      {
        $location = "{$this->host}:{$this->port}";
      }

      $errstr = trim($errstr);
      $this->error = "connection to $location failed (errno=$errno, msg=$errstr)";
      $this->connerror = true;

      return false;
    }

    // send my version
    // this is a subtle part. we must do it before (!) reading back from searchd.
    // because otherwise under some conditions (reported on FreeBSD for instance)
    // TCP stack could throttle write-write-read pattern because of Nagle.
    if (!$this->Send($fp, pack('N', 1), 4))
    {
      fclose($fp);
      $this->error = 'failed to send client protocol version';
      return false;
    }

    // check version
    list(, $v) = unpack('N*', fread($fp, 4));
    $v = (int) $v;
    if ($v < 1)
    {
      fclose($fp);
      $this->error = "expected searchd protocol version 1+, got version '$v'";
      return false;
    }

    return $fp;
  }

  /**
   * get and check response packet from searchd server
   * @param  resource $fp
   * @param  integer  $client_ver
   * @return string
   * @throws Exception
   */
  protected function GetResponse($fp, $client_ver)
  {
    $header = fread($fp, 8);
    list($status, $ver, $len) = array_values(unpack('n2a/Nb', $header));
    $response = '';
    $left = $len;
    while ($left > 0 && !feof($fp))
    {
      $chunk = fread($fp, $left);
      if ($chunk)
      {
        $response .= $chunk;
        $left -= strlen($chunk);
      }
    }
    if ($this->socket === false)
    {
      fclose($fp);
    }

    // check response
    $read = strlen($response);
    if (!$response || $read != $len)
    {
      $this->error = $len
        ? "failed to read searchd response (status=$status, ver=$ver, len=$len, read=$read)"
        : 'received zero-sized searchd response';
      throw new Exception($this->error);
    }

    // check status
    if ($status == self::SEARCHD_ERROR)
    {
      $this->error = 'searchd error: ' . substr($response, 4);
      throw new Exception($this->error);
    }
    if ($status == self::SEARCHD_RETRY)
    {
      $this->error = 'temporary searchd error: ' . substr($response, 4);
      throw new Exception($this->error);
    }
    if ($status != self::SEARCHD_OK)
    {
      $this->error = "unknown status code '$status'";
      throw new Exception($this->error);
    }

    // check version
    if ($ver < $client_ver)
    {
      $this->warning = sprintf(
        "searchd command v.%d.%d older than client's v.%d.%d, some options might not work",
        $ver >> 8, $ver & 0xff, $client_ver >> 8, $client_ver & 0xff);
    }

    return $response;
  }

  /**
   * set match offset, count, and max number to retrieve
   * @param integer $offset
   * @param integer $limit
   * @param integer $max
   */
  public function SetLimits($offset, $limit, $max = 0)
  {
    $this->offset = $offset;
    $this->limit = $limit;
    if ($max > 0)
    {
      $this->maxmatches = $max;
    }
  }

  /**
   * set maximum query time, in milliseconds, per-index
   * integer, 0 means "do not limit"
   * @param integer $max
   */
  public function SetMaxQueryTime($max)
  {
    $this->maxquerytime = $max;
  }

  /**
   * set match mode
   * @param integer $mode
   */
  public function SetMatchMode($mode)
  {
    $this->mode = $mode;
  }

  /**
   * set ranking mode
   * @param integer $ranker
   */
  public function SetRankingMode($ranker)
  {
    $this->ranker = $ranker;
  }

  /**
   * set sort mode
   * @param integer $mode
   * @param string  $sortby
   */
  public function SetSortMode($mode, $sortby = '')
  {
    $this->sort = $mode;
    $this->sortby = $sortby;
  }

  /**
   * set per-field weights
   * @deprecated use SetFieldWeights() instead
   * @param array $weights
   */
  public function SetWeights($weights)
  {
    foreach ($weights as $weight)
    {
      $this->weights = $weights;
    }
  }

  /**
   * bind per-field weights by name
   * @param array $weights
   */
  public function SetFieldWeights(array $weights)
  {
    $this->fieldweights = $weights;
  }

  /**
   * bind per-index weights by name
   * @param array $weights
   */
  public function SetIndexWeights(array $weights)
  {
    $this->indexweights = $weights;
  }

  /**
   * set IDs range to match
   * only match those records where document ID is beetwen $min and $max
   * (including $min and $max)
   * @param integer $min
   * @param integer $max
   */
  public function SetIDRange($min, $max)
  {
    $this->min_id = $min;
    $this->max_id = $max;
  }

  /**
   * set values set filter
   * only match records where $attribute value is in given set
   * @param string  $attribute
   * @param array   $values
   * @param boolean $exclude
   */
  public function SetFilter($attribute, array $values, $exclude = false)
  {
    if (is_array($values) && !empty($values))
    {
      $this->filters[] = array(
        'type'    => self::SPH_FILTER_VALUES,
        'attr'    => $attribute,
        'exclude' => $exclude,
        'values'  => $values,
      );
    }
  }

  /**
   * set range filter
   * only match those records where $attribute column value is beetwen $min and $max
   * (including $min and $max)
   * @param string  $attribute
   * @param integer $min
   * @param integer $max
   * @param boolean $exclude
   */
  public function SetFilterRange($attribute, $min, $max, $exclude = false)
  {
    $this->filters[] = array(
      'type'   => self::SPH_FILTER_RANGE,
      'attr'   => $attribute,
      'exclude'=> $exclude,
      'min'    => $min,
      'max'    => $max,
    );
  }

  /**
   * set float range filter
   * only match records if $attribute value is beetwen $min and $max (inclusive)
   * @param string  $attribute
   * @param integer $max
   * @param integer $min
   * @param boolean $exclude
   */
  public function SetFilterFloatRange($attribute, $min, $max, $exclude = false)
  {
    $this->filters[] = array(
      'type'    => self::SPH_FILTER_FLOATRANGE,
      'attr'    => $attribute,
      'exclude' => $exclude,
      'min'     => $min,
      'max'     => $max,
    );
  }

  /**
   * setup anchor point for geosphere distance calculations
   * required to use @geodist in filters and sorting
   * latitude and longitude must be in radians
   * @param string $attrlat
   * @param string $attrlong
   * @param float  $lat
   * @param float  $long
   */
  public function SetGeoAnchor($attrlat, $attrlong, $lat, $long)
  {
    $this->anchor = array(
      'attrlat'  => $attrlat,
      'attrlong' => $attrlong,
      'lat'      => $lat,
      'long'     => $long,
    );
  }

  /**
   * set grouping attribute and function
   * in grouping mode, all matches are assigned to different groups
   * based on grouping function value.
   *
   * each group keeps track of the total match count, and the best match
   * (in this group) according to current sorting function.
   *
   * the final result set contains one best match per group, with
   * grouping function value and matches count attached. result set
   * is sorted by grouping function value, in descending order.
   *
   * for example, if sorting by relevance and grouping by 'published'
   * attribute with self::SPH_GROUPBY_DAY function, then the result set will
   * contain one most relevant match per each day when there were any
   * matches published, with day number and per-day match count attached,
   * and sorted by day number in descending order (ie. recent days first).
   *
   * @param string  $attribute
   * @param integer $func
   * @apram string  $groupsort
   */
  public function SetGroupBy($attribute, $func, $groupsort = '@group desc')
  {
    $this->groupby = $attribute;
    $this->groupfunc = $func;
    $this->groupsort = $groupsort;
  }

  /**
   * set count-distinct attribute for group-by queries
   * @param string $attribute
   */
  public function SetGroupDistinct($attribute)
  {
    $this->groupdistinct = $attribute;
  }

  /**
   * set distributed retries count and delay
   * @param integer $count
   * @param integer $delay
   */
  public function SetRetries($count, $delay = 0)
  {
    $this->retrycount = $count;
    $this->retrydelay = $delay;
  }

  /**
   * set result set format (hash or array; hash by default)
   * PHP specific; needed for group-by-MVA result sets that may contain duplicate IDs
   * @param boolean $arrayresult
   */
  public function SetArrayResult($arrayresult)
  {
    $this->arrayresult = $arrayresult;
  }

  /**
   * set attribute values override
   * there can be only one override per attribute
   * $values must be a hash that maps document IDs to attribute values
   * @param string  $attrname
   * @param integer $attrtype
   * @param array   $values
   */
  public function SetOverride($attrname, $attrtype, array $values)
  {
    $this->overrides[$attrname] = array(
      'attr'   => $attrname,
      'type'   => $attrtype,
      'values' => $values,
    );
  }

  /**
   * set select-list (attributes or expressions), SQL-like syntax
   * @param string $select
   */
  public function SetSelect($select)
  {
    $this->select = $select;
  }

  /**
   * clear all filters (for multi-queries)
   */
  public function ResetFilters()
  {
    $this->filters = array();
    $this->anchor = array();
  }

  /**
   * clear groupby settings (for multi-queries)
   */
  public function ResetGroupBy()
  {
    $this->groupby = '';
    $this->groupfunc = self::SPH_GROUPBY_DAY;
    $this->groupsort = '@group desc';
    $this->groupdistinct = '';
  }

  /**
   * clear all attribute value overrides (for multi-queries)
   */
  public function ResetOverrides()
  {
    $this->overrides = array();
  }

  /**
   * add a query to $reqs
   * @param  string  $query
   * @param  string  $index
   * @param  string  $comment
   * @return integer          index into results array from RunQueries() call
   */
  public function AddQuery($query, $index = '*', $comment = '')
  {
    // mbstring workaround
    $this->MBPush();

    // build request
    // mode and limits
    $req = pack('NNNNN', $this->offset, $this->limit, $this->mode, $this->ranker, $this->sort);
    $req .= pack('N', strlen($this->sortby)) . $this->sortby;
    $req .= pack('N', strlen($query)) . $query; // query itself
    $req .= pack('N', count($this->weights)); // weights
    foreach ($this->weights as $weight)
    {
      $req .= pack('N', (int)$weight);
    }
    $req .= pack('N', strlen($index)) . $index; // indexes
    $req .= pack('N', 1); // id64 range marker
    $req .= $this->sphPackU64($this->min_id) . $this->sphPackU64($this->max_id); // id64 range

    // filters
    $req .= pack('N', count($this->filters));
    foreach ($this->filters as $filter)
    {
      $req .= pack('N', strlen($filter['attr'])) . $filter['attr'];
      $req .= pack('N', $filter['type']);
      switch ($filter['type'])
      {
        case self::SPH_FILTER_VALUES:
          $req .= pack('N', count($filter['values']));
          foreach ($filter['values'] as $value)
          {
            $req .= $this->sphPackI64($value);
          }
          break;
        case self::SPH_FILTER_RANGE:
          $req .= $this->sphPackI64($filter['min']) . $this->sphPackI64($filter['max']);
          break;
        case self::SPH_FILTER_FLOATRANGE:
          $req .= $this->packFloat($filter['min']) . $this->packFloat($filter['max']);
      }
      $req .= pack('N', $filter['exclude']);
    }

    // group-by clause, max-matches count, group-sort clause, cutoff count
    $req .= pack('NN', $this->groupfunc, strlen($this->groupby)) . $this->groupby;
    $req .= pack('N', $this->maxmatches);
    $req .= pack('N', strlen($this->groupsort)) . $this->groupsort;
    $req .= pack('NNN', $this->cutoff, $this->retrycount, $this->retrydelay);
    $req .= pack('N', strlen($this->groupdistinct)) . $this->groupdistinct;

    // anchor point
    if (empty($this->anchor))
    {
      $req .= pack('N', 0);
    }
    else
    {
      $a = $this->anchor;
      $req .= pack('N', 1);
      $req .= pack('N', strlen($a['attrlat'])) . $a['attrlat'];
      $req .= pack('N', strlen($a['attrlong'])) . $a['attrlong'];
      $req .= $this->packFloat($a['lat']) . $this->packFloat($a['long']);
    }

    // per-index weights
    $req .= pack('N', count($this->indexweights));
    foreach ($this->indexweights as $idx=>$weight)
    {
      $req .= pack('N', strlen($idx)) . $idx . pack('N', $weight);
    }

    // max query time
    $req .= pack('N', $this->maxquerytime);

    // per-field weights
    $req .= pack('N', count($this->fieldweights));
    foreach ($this->fieldweights as $field=>$weight)
    $req .= pack('N', strlen($field)) . $field . pack('N', $weight);

    // comment
    $req .= pack('N', strlen($comment)) . $comment;

    // attribute overrides
    $req .= pack('N', count($this->overrides));
    foreach ($this->overrides as $key => $entry)
    {
      $req .= pack('N', strlen($entry['attr'])) . $entry['attr'];
      $req .= pack('NN', $entry['type'], count($entry['values']));
      foreach ($entry['values'] as $id => $val)
      {
        $req .= $this->sphPackU64($id);
        switch ($entry['type'])
        {
          case self::SPH_ATTR_FLOAT:
            $req .= $this->PackFloat($val);
            break;
          case self::SPH_ATTR_BIGINT:
            $req .= $this->sphPackI64($val);
            break;
          default:
            $req .= pack('N', $val);
        }
      }
    }

    // select-list
    $req .= pack('N', strlen($this->select)) . $this->select;

    // mbstring workaround
    $this->MBPop();

    // store request to requests array
    $this->reqs[] = $req;

    return count($this->reqs) - 1;
  }

  /**
   * connect to searchd server and run given search query
   *
   * @param  string  $query
   * @param  string  $index   index name to query, default is '*' which means to query all indexes
   * @param  string  $comment
   * @return array            array with following keys:
   *                          'matches'
   *                            hash which maps found document_id to ('weight', 'group') hash
   *                          'total'
   *                            amount of matches retrieved (upto self::SPH_MAX_MATCHES)
   *                          'total_found'
   *                            amount of matching documents in index
   *                          'time'
   *                            search time
   *                          'words'
   *                            hash which maps query terms (stemmed!) to ('docs', 'hits') hash
   * @throws Exception
   */
  public function Query($query, $index = '*', $comment = '')
  {
    $this->AddQuery($query, $index, $comment);
    $results = $this->RunQueries();
    if (!is_array($results))
    {
      // probably network error; error message should be already filled
      throw new Exception($this->error);
    }
    $this->error = $results[0]['error'];
    $this->warning = $results[0]['warning'];
    if ($results[0]['status'] == self::SEARCHD_ERROR)
    {
      throw new Exception($this->error);
    }
    else
    {
      $this->res = $results[0];
      return $this->res;
    }
  }

  /**
   * get query results
   * @return array  See Query() method
   */
  public function getRes()
  {
    return $this->res;
  }

  /**
   * connect to searchd, run queries batch, and return an array of result sets
   * @return array
   */
  public function RunQueries()
  {
    if (empty($this->reqs))
    {
      $this->error = 'no queries defined, issue AddQuery() first';
      throw new Exception($this->error);
    }

    // mbstring workaround
    $this->MBPush();

    if (!($fp = $this->Connect()))
    {
      $this->MBPop();
      throw new Exception($this->error);
    }

    //// send query, get response

    $nreqs = count($this->reqs);
    $req = join('', $this->reqs);
    $len = 4 + strlen($req);
    // add header
    $req = pack('nnNN', self::SEARCHD_COMMAND_SEARCH, self::VER_COMMAND_SEARCH, $len, $nreqs) . $req;

    if (!($this->Send($fp, $req, $len + 8)) || !($response = $this->GetResponse($fp, self::VER_COMMAND_SEARCH)))
    {
      $this->MBPop();
      throw new Exception($this->error);
    }

    $this->reqs = array();

    return $this->ParseSearchResponse($response, $nreqs);
  }

  /**
   * parse and return search query (or queries) response
   * @param string  $response
   * @param integer $nreqs
   */
  protected function ParseSearchResponse($response, $nreqs)
  {
    //// parse response

    $p = 0; // current position
    $max = strlen($response); // max position for checks, to protect against broken responses

    $results = array();
    for ($ires = 0; $ires < $nreqs && $p < $max; $ires ++)
    {
      $results[] = array();
      $result =& $results[$ires];

      $result['error'] = '';
      $result['warning'] = '';

      // extract status
      list(, $status) = unpack('N*', substr($response, $p, 4));
      $p += 4;
      $result['status'] = $status;
      if ($status != self::SEARCHD_OK)
      {
        list(, $len) = unpack('N*', substr($response, $p, 4));
        $p += 4;
        $message = substr($response, $p, $len);
        $p += $len;

        if ($status == self::SEARCHD_WARNING)
        {
          $result['warning'] = $message;
        }
        else
        {
          $result['error'] = $message;
          continue;
        }
      }

      // read schema
      $fields = array();
      $attrs = array();

      list(, $nfields) = unpack('N*', substr($response, $p, 4));
      $p += 4;
      while ($nfields -- > 0 && $p < $max)
      {
        list(, $len) = unpack('N*', substr($response, $p, 4));
        $p += 4;
        $fields[] = substr($response, $p, $len);
        $p += $len;
      }
      $result['fields'] = $fields;

      list(, $nattrs) = unpack('N*', substr($response, $p, 4));
      $p += 4;
      while ($nattrs -- > 0 && $p < $max)
      {
        list(, $len) = unpack('N*', substr($response, $p, 4));
        $p += 4;
        $attr = substr($response, $p, $len);
        $p += $len;
        list(, $type) = unpack('N*', substr($response, $p, 4));
        $p += 4;
        $attrs[$attr] = $type;
      }
      $result['attrs'] = $attrs;
      // read match count
      list(, $count) = unpack('N*', substr($response, $p, 4));
      $p += 4;
      list(, $id64) = unpack('N*', substr($response, $p, 4));
      $p += 4;

      // read matches
      $idx = -1;
      while ($count -- > 0 && $p < $max)
      {
        // index into result array
        $idx ++;

        // parse document id and weight
        if ($id64)
        {
          $doc = $this->sphUnpackU64(substr($response, $p, 8));
          $p += 8;
          list(, $weight) = unpack('N*', substr($response, $p, 4));
          $p += 4;
        }
        else
        {
          list($doc, $weight) = array_values(unpack('N*N*', substr($response, $p, 8)));
          $p += 8;

          if (PHP_INT_SIZE >= 8)
          {
            // x64 route, workaround broken unpack() in 5.2.2+
            if ($doc < 0)
            {
              $doc += (1 << 32);
            }
          }
          else
          {
            // x32 route, workaround php signed/unsigned braindamage
            $doc = sprintf('%u', $doc);
          }
        }
        $weight = sprintf('%u', $weight);

        // create match entry
        if ($this->arrayresult)
        {
         $result['matches'][$idx] = array('id' => $doc, 'weight' => $weight);
        }
        else
        {
         $result['matches'][$doc]['weight'] = $weight;
        }

        // parse and create attributes
        $attrvals = array();
        foreach ($attrs as $attr => $type)
        {
          // handle 64bit ints
          if ($type == self::SPH_ATTR_BIGINT)
          {
            $attrvals[$attr] = sphUnpackI64(substr($response, $p, 8));
            $p += 8;
            continue;
          }

          // handle floats
          if ($type == self::SPH_ATTR_FLOAT)
          {
            list(, $uval) = unpack('N*', substr($response, $p, 4));
            $p += 4;
            list(, $fval) = unpack('f*', pack('L', $uval));
            $attrvals[$attr] = $fval;
            continue;
          }

          // handle everything else as unsigned ints
          list(, $val) = unpack('N*', substr($response, $p, 4));
          $p += 4;
          if ($type & self::SPH_ATTR_MULTI)
          {
            $attrvals[$attr] = array();
            $nvalues = $val;
            while ($nvalues -- > 0 && $p < $max)
            {
              list(, $val) = unpack('N*', substr($response, $p, 4));
              $p += 4;
              $attrvals[$attr][] = sprintf('%u', $val);
            }
          }
          elseif ($type == self::SPH_ATTR_STRING)
          {
            $attrvals[$attr] = substr($response, $p, $val);
            $p += $val;
          }
          else
          {
            $attrvals[$attr] = sprintf('%u', $val);
          }
        }

        if ($this->arrayresult)
        {
         $result['matches'][$idx]['attrs'] = $attrvals;
        }
        else
        {
         $result['matches'][$doc]['attrs'] = $attrvals;
        }
      }

      list($total, $total_found, $msecs, $words) = array_values(unpack('N*N*N*N*', substr($response, $p, 16)));
      $result['total'] = sprintf('%u', $total);
      $result['total_found'] = sprintf('%u', $total_found);
      $result['time'] = sprintf('%.3f', $msecs / 1000);
      $p += 16;

      while ($words -- > 0 && $p < $max)
      {
        list(, $len) = unpack('N*', substr($response, $p, 4));
        $p += 4;
        $word = substr($response, $p, $len);
        $p += $len;
        list($docs, $hits) = array_values(unpack('N*N*', substr($response, $p, 8)));
        $p += 8;
        $result['words'][$word] = array (
          'docs' => sprintf('%u', $docs),
          'hits' => sprintf('%u', $hits));
      }
    }

    $this->MBPop();

    return $results;
  }

  /**
   * excerpts generation
   * connect to searchd server and generate exceprts from given documents
   *
   * @param array  $docs  strings representing the documents' contents
   * @param string $index index which settings will be used for stemming, lexing and case folding
   * @param string $words words to highlight
   * @param array  $opts  additional optional highlighting parameters:
   *                        'before_match'
   *                          a string to insert before a set of matching words, default is '<b>'
   *                        'after_match'
   *                          a string to insert after a set of matching words, default is '<b>'
   *                        'chunk_separator'
   *                          a string to insert between excerpts chunks, default is ' ... '
   *                        'limit'
   *                          max excerpt size in symbols (codepoints), default is 256
   *                        'around'
   *                          how much words to highlight around each match, default is 5
   * @return array        strings of excerpts
   * @throws Exception
   */
  public function BuildExcerpts($docs, $index, $words, $opts = array())
  {

    $this->MBPush();
    if (!($fp = $this->Connect()))
    {
      $this->MBPop();
      throw new Exception($this->error);
    }

    //// fixup options

    if (!isset($opts['before_match']))     $opts['before_match'] = '<b>';
    if (!isset($opts['after_match']))      $opts['after_match'] = '</b>';
    if (!isset($opts['chunk_separator']))  $opts['chunk_separator'] = ' ... ';
    if (!isset($opts['limit']))            $opts['limit'] = 256;
    if (!isset($opts['limit_passages']))   $opts['limit_passages'] = 0;
    if (!isset($opts['limit_words']))      $opts['limit_words'] = 0;
    if (!isset($opts['around']))           $opts['around'] = 5;
    if (!isset($opts['exact_phrase']))     $opts['exact_phrase'] = false;
    if (!isset($opts['single_passage']))   $opts['single_passage'] = false;
    if (!isset($opts['use_boundaries']))   $opts['use_boundaries'] = false;
    if (!isset($opts['weight_order']))     $opts['weight_order'] = false;
    if (!isset($opts['query_mode']))       $opts['query_mode'] = false;
    if (!isset($opts['force_all_words']))  $opts['force_all_words'] = false;
    if (!isset($opts['start_passage_id'])) $opts['start_passage_id'] = 1;
    if (!isset($opts['load_files']))       $opts['load_files'] = false;
    if (!isset($opts['html_strip_mode']))  $opts['html_strip_mode'] = 'index';
    if (!isset($opts['allow_empty']))      $opts['allow_empty'] = false;

    //// build request

    // v.1.2 req
    $flags = 1; // remove spaces
    if ($opts['exact_phrase'])    $flags |= 2;
    if ($opts['single_passage'])  $flags |= 4;
    if ($opts['use_boundaries'])  $flags |= 8;
    if ($opts['weight_order'])    $flags |= 16;
    if ($opts['query_mode'])      $flags |= 32;
    if ($opts['force_all_words']) $flags |= 64;
    if ($opts['load_files'])      $flags |= 128;
    if ($opts['allow_empty'])     $flags |= 256;
    $req = pack('NN', 0, $flags); // mode=0, flags=$flags
    $req .= pack('N', strlen($index)) . $index; // req index
    $req .= pack('N', strlen($words)) . $words; // req words

    // options
    $req .= pack('N', strlen($opts['before_match'])) . $opts['before_match'];
    $req .= pack('N', strlen($opts['after_match'])) . $opts['after_match'];
    $req .= pack('N', strlen($opts['chunk_separator'])) . $opts['chunk_separator'];
    $req .= pack('N', (int)$opts['limit']);
    $req .= pack('N', (int)$opts['around']);

    // documents
    $req .= pack('N', count($docs));
    foreach ($docs as $doc)
    {
      $req .= pack('N', strlen($doc)) . $doc;
    }

    //// send query, get response

    $len = strlen($req);
    // add header
    $req = pack('nnN', self::SEARCHD_COMMAND_EXCERPT, self::VER_COMMAND_EXCERPT, $len) . $req;
    if (!($this->Send($fp, $req, $len + 8)) || !($response = $this->GetResponse($fp, self::VER_COMMAND_EXCERPT)))
    {
      $this->MBPop();
      throw new Exception($this->error);
    }

    //// parse response

    $pos = 0;
    $res = array();
    $rlen = strlen($response);
    for ($i = 0, $c = count($docs); $i < $c; $i ++)
    {
      list(, $len) = unpack('N*', substr($response, $pos, 4));
      $pos += 4;

      if ($pos + $len > $rlen)
      {
        $this->error = 'incomplete reply';
        $this->MBPop();
        throw new Exception($this->error);
      }
      $res[] = substr($response, $pos, $len);
      $pos += $len;
    }

    $this->MBPop();

    return $res;
  }

  /**
   * connect to searchd server, and generate keyword list for a given query
   * @param  string  $query
   * @param  string  $index
   * @param  boolean $hits
   * @return array
   * @throws Exception
   */
  public function BuildKeywords($query, $index, $hits)
  {
    $this->MBPush();

    if (!($fp = $this->Connect()))
    {
      $this->MBPop();
      throw new Exception($this->error);
    }

    //// build request

    // v.1.0 req
    $req  = pack('N', strlen($query)) . $query; // req query
    $req .= pack('N', strlen($index)) . $index; // req index
    $req .= pack('N', (int)$hits);

    //// send query, get response

    $len = strlen($req);
    $req = pack('nnN', self::SEARCHD_COMMAND_KEYWORDS, self::VER_COMMAND_KEYWORDS, $len) . $req; // add header
    if (!($this->Send($fp, $req, $len + 8)) || !($response = $this->GetResponse($fp, self::VER_COMMAND_KEYWORDS)))
    {
      $this->MBPop();
      throw new Exception($this->error);
    }

    //// parse response

    $pos = 0;
    $res = array();
    $rlen = strlen($response);
    list(, $nwords) = unpack('N*', substr($response, $pos, 4));
    $pos += 4;
    for($i = 0; $i < $nwords; $i ++)
    {
      list(, $len) = unpack('N*', substr($response, $pos, 4));
      $pos += 4;
      $tokenized = $len ? substr($response, $pos, $len) : '';
      $pos += $len;

      list(, $len) = unpack('N*', substr($response, $pos, 4));
      $pos += 4;
      $normalized = $len ? substr($response, $pos, $len) : '';
      $pos += $len;

      $res[] = array('tokenized' => $tokenized, 'normalized' => $normalized);

      if ($hits)
      {
        list($ndocs, $nhits) = array_values(unpack('N*N*', substr($response, $pos, 8)));
        $pos += 8;
        $res [$i]['docs'] = $ndocs;
        $res [$i]['hits'] = $nhits;
      }

      if ($pos > $rlen)
      {
        $this->error = 'incomplete reply';
        $this->MBPop();
        throw new Exception($this->error);
      }
    }

    $this->MBPop();

    return $res;
  }

  /**
   * update given attribute values on given documents in given indexes
   * @param  string  $index
   * @param  array   $attrs
   * @param  array   $values
   * @param  boolean $mva
   * @return integer         amount of updated documents (0 or more) on success, or -1 on failure
   */
  public function UpdateAttributes($index, array $attrs, array $values, $mva = false)
  {
    // build request
    $this->MBPush();
    $req = pack('N', strlen($index)) . $index;

    $req .= pack('N', count($attrs));
    foreach ($attrs as $attr)
    {
      $req .= pack('N', strlen($attr)) . $attr;
      $req .= pack('N', $mva ? 1 : 0);
    }

    $req .= pack('N', count($values));
    foreach ($values as $id => $entry)
    {
      $req .= $this->sphPackU64($id);
      foreach ($entry as $v)
      {
        $req .= pack('N', $mva ? count($v) : $v);
        if ($mva)
        {
          foreach ($v as $vv)
          {
            $req .= pack('N', $vv);
          }
        }
      }
    }

    // connect, send query, get response
    if (!($fp = $this->Connect()))
    {
      $this->MBPop();
      return -1;
    }

    $len = strlen($req);
    // add header
    $req = pack('nnN', self::SEARCHD_COMMAND_UPDATE, self::VER_COMMAND_UPDATE, $len) . $req;
    if (!$this->Send($fp, $req, $len + 8))
    {
      $this->MBPop();
      return -1;
    }

    if (!($response = $this->GetResponse($fp, self::VER_COMMAND_UPDATE)))
    {
      $this->MBPop();
      return -1;
    }

    // parse response
    list(, $updated) = unpack('N*', substr($response, 0, 4));

    $this->MBPop();

    return $updated;
  }

  /**
   * open a persistent connection
   * @return boolean
   */
  public function Open()
  {
    if ($this->socket !== false)
    {
      $this->error = 'already connected';
      return false;
    }
    if (!$fp = $this->Connect())
    {
      return false;
    }

    // command, command version = 0, body length = 4, body = 1
    $req = pack('nnNN', self::SEARCHD_COMMAND_PERSIST, 0, 4, 1);
    if (!$this->Send ($fp, $req, 12))
    {
      return false;
    }

    $this->socket = $fp;

    return true;
  }

  /**
   * close a persistent connection
   * @return boolean
   */
  public function Close()
  {
    if ($this->socket === false)
    {
      $this->error = 'not connected';
      return false;
    }

    fclose($this->socket);
    $this->socket = false;

    return true;
  }

  /**
   * status
   * @return array
   */
  public function Status()
  {
    $this->MBPush();
    if (!($fp = $this->Connect()))
    {
      $this->MBPop();
      return false;
    }

    $req = pack('nnNN', self::SEARCHD_COMMAND_STATUS, self::VER_COMMAND_STATUS, 4, 1); // len=4, body=1
    if (!($this->Send($fp, $req, 12)) || !($response = $this->GetResponse($fp, self::VER_COMMAND_STATUS)))
    {
      $this->MBPop();
      return false;
    }

    $res = substr($response, 4); // just ignore length, error handling, etc
    $p = 0;
    list($rows, $cols) = array_values(unpack('N*N*', substr($response, $p, 8)));
    $p += 8;

    $res = array();
    for ($i = 0; $i < $rows; $i ++)
    {
      for ($j = 0; $j < $cols; $j ++)
      {
        list(,$len) = unpack('N*', substr($response, $p, 4));
        $p += 4;
        $res[$i][] = substr($response, $p, $len);
        $p += $len;
      }
    }

    $this->MBPop();

    return $res;
  }

  /**
   * flush
   * @return string
   */
  function FlushAttributes()
  {
    $this->MBPush();
    if (!($fp = $this->Connect()))
    {
      $this->MBPop();
      return -1;
    }

    $req = pack('nnN', self::SEARCHD_COMMAND_FLUSHATTRS, self::VER_COMMAND_FLUSHATTRS, 0); // len=0
    if (!($this->Send($fp, $req, 8)) || !($response = $this->GetResponse($fp, self::VER_COMMAND_FLUSHATTRS)))
    {
      $this->MBPop();
      return -1;
    }

    $tag = -1;
    if (strlen($response) == 4)
    {
      list(, $tag) = unpack('N*', $response);
    }
    else
    {
      $this->error = 'unexpected response length';
    }

    $this->MBPop();

    return $tag;
  }

}
