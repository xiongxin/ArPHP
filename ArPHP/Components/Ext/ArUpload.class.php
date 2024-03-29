<?php
/**
 * ArPHP A Strong Performence PHP FrameWork ! You Should Have.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Core.Component
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.net/licence BSD Licence
 * @version  GIT: 1: coding-standard-tutorial.xml,v 1.0 2014-5-01 18:16:25 cweiske Exp $
 * @link     http://www.arphp.net
 */

/**
 * l
 *
 * default hash comment :
 *
 * <code>
 *  # This is a hash comment, which is prohibited.
 *  $hello = 'hello';
 * </code>
 *
 * @category ArPHP
 * @package  Core.Component
 * @author   yc <ycassnr@gmail.com>
 * @license  http://www.arphp.net/licence BSD Licence
 * @version  Release: @package_version@
 * @link     http://www.arphp.net
 */
class ArUpload extends ArComponent
{
    // upload destination folder
    public $dest = '';
    // upload error
    public $errorMsg = null;
    // upload field
    protected $upField = '';

    /**
     * get errorMsg.
     *
     * @return $mixed
     */
    public function errorMsg()
    {
        return $this->errorMsg;

    }

    // mimemap
    static public $mimeMap = array(

            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',

        );

    /**
     * upload.
     *
     * @param string $upField   upload field.
     * @param string $dest      upload destination.
     * @param string $extension allow file extension.
     *
     * @return mixed
     */
    public function upload($upField, $dest = '', $extension = 'all')
    {
        $this->errorMsg = null;

        $this->upField = $upField;

        if (!empty($_FILES[$this->upField]) && is_uploaded_file($_FILES[$this->upField]['tmp_name'])) :
            if ($extension == 'all' || $this->checkFileType($_FILES[$this->upField]['type'], $extension)) :
                $dest = empty($dest) ? arCfg('PATH.UPLOAD') : $dest;

                if (!is_dir($dest)) :
                    mkdir($dest);
                endif;

                $upFileName = $this->generateFileName();
                $destFile = rtrim($dest, DS) . DS . $upFileName;

                if (move_uploaded_file($_FILES[$this->upField]['tmp_name'], $destFile)) :

                else :
                    $this->errorMsg = '上传出错';
                endif;

            endif;

        else :
            $this->errorMsg = "Filed '$upField' invalid";
        endif;

        if (!!$this->errorMsg) :
            return false;
        else :
            return $upFileName;
        endif;

    }

    /**
     * check file type valided.
     *
     * @param string $fileType  fileType.
     * @param string $extension file ext.
     *
     * @return boolean
     */
    protected function checkFileType($fileType, $extension)
    {
        if ($extension == 'img') :
            if (!in_array($fileType, array(self::$mimeMap['jpg'], self::$mimeMap['png'], self::$mimeMap['gif']))) :
                $this->errorMsg = "仅支持图片类型";
            endif;
        elseif (empty(self::$mimeMap[$extension])) :

            $this->errorMsg = ".{$extension}不支持的上传类型,支持的类型:" . implode(',', self::$mimeMap);

        else :
            if ($fileType != self::$mimeMap[$extension]) :
                $this->errorMsg ="仅支持.{$extension}类型";
            endif;
        endif;

        return !$this->errorMsg;

    }

    /**
     * generate filename.
     *
     * @return string
     */
    protected function generateFileName()
    {
        return md5(time() . rand()) . '.' . substr($_FILES[$this->upField]['name'], strrpos($_FILES[$this->upField]['name'], '.') + 1);

    }

}
