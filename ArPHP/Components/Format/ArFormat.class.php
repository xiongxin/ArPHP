<?php
/**
 * ArPHP A Strong Performence PHP FrameWork ! You Should Have.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Core.Component.Format
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.net/licence BSD Licence
 * @version  GIT: 1: coding-standard-tutorial.xml,v 1.0 2014-5-01 18:16:25 cweiske Exp $
 * @link     http://www.arphp.net
 */

/**
 * ArFormat
 *
 * default hash comment :
 *
 * <code>
 *  # This is a hash comment, which is prohibited.
 *  $hello = 'hello';
 * </code>
 *
 * @category ArPHP
 * @package  Core.Component.Format
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.net/licence BSD Licence
 * @version  Release: @package_version@
 * @link     http://www.arphp.net
 */
class ArFormat extends ArComponent
{
    /**
     * time to date.
     *
     * @param mixed  $obj data.
     * @param string $key key to trans.
     *
     * @return mixed
     */
    public function timeToDate($obj, $key = '')
    {
        if (Ar::c('validator.validator')->checkMutiArray($obj)) :
            foreach ($obj as &$time) :
                $time = $this->timeToDate($time, $key);
            endforeach;
        elseif (is_array($obj)) :
            if (isset($obj[$key])) :
                $obj[$key] = $this->timeToDate($obj[$key]);
            endif;
        else :
            $obj = date('Y-m-d', Ar::c('validator.validator')->checkNumber($obj) ? $obj : strtotime($obj));
        endif;

        return $obj;

    }

    /**
     * replace.
     *
     * @param string $key   key.
     * @param mixed  $value value.
     * @param mixed  $obj   obj.
     *
     * @return mixed
     */
    public function replace($key, $value, $obj)
    {
        if (is_array($obj)) :
            foreach($obj as &$o) :
                $o = $this->replace($key, $value, $o);
            endforeach;
        else :
            $obj = str_replace($key, $value, $obj);
        endif;
        return $obj;

    }

    /**
     * filter.
     *
     * @param mixed $obj obj.
     *
     * @return mixed
     */
    public function stripslashes($obj)
    {
        if (is_array($obj)) :
            foreach($obj as &$o) :
                $o = $this->stripslashes($o);
            endforeach;
        else :
            $obj = stripslashes($obj);
        endif;
        return $obj;

    }

    /**
     * trim.
     *
     * @param string $obj object.
     *
     * @return mixed
     */
    public function trim($obj)
    {
        if (is_array($obj)) :
            foreach($obj as &$o) :
                $o = $this->trim($o);
            endforeach;
        else :
            $obj = trim($obj);
        endif;
        return $obj;

    }

    /**
     * encrypt.
     *
     * @param mixed  $obj obj.
     * @param string $key key.
     *
     * @return mixed
     */
    public function encrypt($obj, $key = '')
    {
        if (is_array($obj)) :
            if (empty($obj[$key])) :
                foreach ($obj as &$eObj) :
                    $eObj = $this->encrypt($eObj, $key);
                endforeach;
            else :
                $obj[$key] = $this->encrypt($obj[$key]);
            endif;
        else :
            $obj = Ar::c('hash.mcrypt')->encrypt($obj);
        endif;

        return $obj;

    }

    /**
     * decode.
     *
     * @param mixed  $obj obj.
     * @param string $key key.
     *
     * @return mixed
     */
    public function urldecode($obj, $key = '')
    {
        if (is_array($obj)) :
            if (empty($obj[$key])) :
                foreach ($obj as &$eObj) :
                    $eObj = $this->urldecode($eObj, $key);
                endforeach;
            else :
                $obj[$key] = $this->urldecode($obj[$key]);
            endif;
        else :
            $obj = urldecode($obj);
        endif;

        return $obj;

    }

    /**
     * urlencode.
     *
     * @param mixed  $obj obj.
     * @param string $key key.
     *
     * @return mixed
     */
    public function urlencode($obj, $key = '')
    {
        if (is_array($obj)) :
            if (empty($obj[$key])) :
                foreach ($obj as &$eObj) :
                    $eObj = $this->urlencode($eObj, $key);
                endforeach;
            else :
                $obj[$key] = $this->urlencode($obj[$key]);
            endif;
        else :
            $obj = urlencode($obj);
        endif;

        return $obj;

    }

    /**
     * add slashes for mixed params.
     *
     * @return array
     */
    public function addslashes()
    {
        $args = func_get_args();
        foreach ($args as $k => &$arg) :
            if (is_array($arg) || is_object($arg)) :
                foreach ($arg as $v => &$narg) :
                    $narg = is_scalar($narg) ? addslashes($narg) : $this->addslashes($narg);
                endforeach;
            else :
                $arg = addslashes($arg);
            endif;
        endforeach;

        if (count($args) == 1) :
            $args = $args[0];
        endif;
        return $args;

    }

    /**
     * filter array other key.
     *
     * @param array $gar  origin array.
     * @param array $data key array.
     *
     * @return array
     */
    public function filterKey(array $gar, array $data)
    {
        foreach ($data as $k => $v) :
            if (is_numeric($k) || !in_array($k, $gar)) :
                unset($data[$k]);
            endif;
        endforeach;

        return $data;

    }

}
