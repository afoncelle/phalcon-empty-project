<?php

namespace Cryptokart\Open\Controllers;

use Phalcon\Mvc\View;
use Cryptokart\Controllers\ControllerBase;

/*
 * Copyright(C) CryptoKart from 2022 to present
 * All rights reserved
 * @author afoncelle
 */
class PublicController extends ControllerBase {
    
    public function initialize()
    {
        parent::initialize();
    }

    public function robotsAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    public function faviconAction()
    {
        try {
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            $dir = BASE_PATH . '/public/img/rocket/';
            $file = "{$dir}/favicon.ico";
            $size = getimagesize($file);
            header('Content-Type: '.$size['mime']);
            header('Content-Length: '.filesize($file));
            header("Cache-Control: max-age=2592000");
            readfile($file);
        } catch (\Throwable $t){
            return $t;
        }
    }

    public function indexAction()
    {

    }

    private function checkToken($token, $secret_key)
    {
        $verif_url  = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$token";
        $curl = curl_init($verif_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $verif_response = curl_exec($curl);
        if ( empty($verif_response) ) return false;
        else {
            $json = json_decode($verif_response);
            return $json->success;
        }
    }

    private function filter_filename($filename, $beautify=true) {
        $filename = htmlentities($filename, ENT_NOQUOTES, 'utf-8');
        $filename = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $filename);
        $filename = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $filename);
        $filename = preg_replace('#&[^;]+;#', '', $filename);
        // sanitize filename
        $filename = preg_replace(
            '~
        [<>:"/\\|?*]|            # file system reserved https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
        [\x00-\x1F]|             # control characters http://msdn.microsoft.com/en-us/library/windows/desktop/aa365247%28v=vs.85%29.aspx
        [\x7F\xA0\xAD]|          # non-printing characters DEL, NO-BREAK SPACE, SOFT HYPHEN
        [#\[\]@!$&\'()+,;=]|     # URI reserved https://www.rfc-editor.org/rfc/rfc3986#section-2.2
        [{}^\~`]                 # URL unsafe characters https://www.ietf.org/rfc/rfc1738.txt
        ~x',
            '-', $filename);
        // avoids ".", ".." or ".hiddenFiles"
        $filename = ltrim($filename, '.-');
        // optional beautification
        if ($beautify) $filename = $this->beautify_filename($filename);
        // maximize filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = mb_strcut(pathinfo($filename, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($filename)) . ($ext ? '.' . $ext : '');
        return $filename;
    }

    private function beautify_filename($filename) {
        // reduce consecutive characters
        $filename = preg_replace(array(
            // "file   name.zip" becomes "file-name.zip"
            '/ +/',
            // "file___name.zip" becomes "file-name.zip"
            '/_+/',
            // "file---name.zip" becomes "file-name.zip"
            '/-+/'
        ), '-', $filename);
        $filename = preg_replace(array(
            // "file--.--.-.--name.zip" becomes "file.name.zip"
            '/-*\.-*/',
            // "file...name..zip" becomes "file.name.zip"
            '/\.{2,}/'
        ), '.', $filename);
        // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
        $filename = mb_strtolower($filename, mb_detect_encoding($filename));
        // ".file-name.-" becomes "file-name"
        $filename = trim($filename, '.-');
        return $filename;
    }
    
    /**
     * @param $image_path
     * @return bool|mixed
     */
    private function get_image_mime_type($image_path)
    {
        $mimes  = array(
            IMAGETYPE_GIF => "gif",
            IMAGETYPE_JPEG => "jpg",
            IMAGETYPE_PNG => "png",
            IMAGETYPE_SWF => "swf",
            IMAGETYPE_PSD => "psd",
            IMAGETYPE_BMP => "bmp",
            IMAGETYPE_TIFF_II => "tiff",
            IMAGETYPE_TIFF_MM => "tiff",
            IMAGETYPE_JPC => "jpc",
            IMAGETYPE_JP2 => "jp2",
            IMAGETYPE_JPX => "jpx",
            IMAGETYPE_JB2 => "jb2",
            IMAGETYPE_SWC => "swc",
            IMAGETYPE_IFF => "iff",
            IMAGETYPE_WBMP => "wbmp",
            IMAGETYPE_XBM => "xbm",
            IMAGETYPE_ICO => "ico");

        if (($image_type = \exif_imagetype($image_path))
            && (array_key_exists($image_type ,$mimes)))
        {
            return $mimes[$image_type];
        }
        else
        {
            return FALSE;
        }
    }

    public function gascriptAction($analitycsId)
    {
        header("content-type: application/javascript");
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        echo file_get_contents("https://www.googletagmanager.com/gtag/js?id={$analitycsId}");
    }

}