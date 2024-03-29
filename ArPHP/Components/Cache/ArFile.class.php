<?php
/**
 * ArPHP A Strong Performence PHP FrameWork ! You Should Have.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Core.Components.Cache
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.net/licence BSD Licence
 * @version  GIT: 1: coding-standard-tutorial.xml,v 1.0 2014-5-01 18:16:25 cweiske Exp $
 * @link     http://www.arphp.net
 */

/**
 * class file cache
 *
 * default hash comment :
 *
 * <code>
 *  # This is a hash comment, which is prohibited.
 *  $hello = 'hello';
 * </code>
 *
 * @category ArPHP
 * @package  Core.base
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.net/licence BSD Licence
 * @version  Release: @package_version@
 * @link     http://www.arphp.net
 */
class ArFile extends ArCache
{
    // cache path
    public $cachePath;

    /**
     * initialization function.
     *
     * @param mixed  $config config.
     * @param string $class  hold class.
     *
     * @return Object
     */
    static public function init($config = array(), $class = __CLASS__)
    {
        $obj = parent::init($config, $class);

        $obj->cachePath = empty(self::$config['cachePath']) ? arCfg('PATH.CACHE') : self::$config['cachePath'];

        if(!is_dir($obj->cachePath)) :
            mkdir($obj->cachePath, 0777, true);
        endif;

        return $obj;

    }

    /**
     * get cache file name.
     *
     * @param string $key cache key.
     *
     * @return string
     */
    public function cacheFile($key)
    {
        return $this->cachePath . $this->generateUniqueKey($key) . '.cache';

    }

    /**
     * cache get
     *
     * @param string $key cache key.
     *
     * @return mixed
     */
    public function get($key)
    {
        $cacheFile = $this->cacheFile($key);

        if (is_file($cacheFile)) :
            if ($this->checkExpire($cacheFile)) :
                $data = null;
                $this->del($key);
            endif;
            $data = $this->decrypt(file_get_contents($cacheFile, false, null, 10));
        else :
            $data = null;
        endif;

        return $data;

    }

    /**
     * cache set.
     *
     * @param string  $key    cache key.
     * @param mixed   $value  value.
     * @param integer $expire time.
     *
     * @return mixed
     */
    public function set($key, $value, $expire = 0)
    {
        if ($expire == 0) :
            $timeExpire = '0000000000';
        else :
            $timeExpire = time() + $expire;
        endif;

        return file_put_contents($this->cacheFile($key), $timeExpire . $this->encrypt($value));

    }

    /**
     * cache del.
     *
     * @param string $key cache key.
     *
     * @return mixed
     */
    public function del($key)
    {
        $cacheFile = $this->cacheFile($key);

        if (is_file($cacheFile)) :
            unlink($cacheFile);
        endif;

        return true;

    }

    /**
     * check cache valid.
     *
     * @param string $file file.
     *
     * @return boolean
     */
    public function checkExpire($file)
    {
        $timeExpire = file_get_contents($file, false, null, 0, 10);

        return $timeExpire == 0 ? false : ($timeExpire < time());

    }

    /**
     * cache flush.
     *
     * @param boolean $force cleal all cache.
     *
     * @return mixed
     */
    public function flush($force = false)
    {
        $source = opendir($this->cachePath);

        while ($file = readdir($source)) :
            $file = $this->cachePath . $file;
            if (is_file($file)) :
                if ($force || $this->checkExpire($file)) :
                    unlink($file);
                endif;
            endif;
        endwhile;

        closedir($source);
    }

}
