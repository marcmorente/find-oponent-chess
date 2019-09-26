<?php

/**
 * 
 *
 * @author Marc Morente
 */
class UploadPGN
{

    private $fileName;
    private $type;
    private $tmpName;
    private $error;
    private $size;
    private $extension;
    private $validPGN = ['application/x-chess-pgn', 'application/da-chess-pgn'];

    public function __construct($file)
    {
        $this->fileName     = $this->cleanName($file['name']);
        $this->type         = $file['type'];
        $this->tmpName      = $file['tmp_name'];
        $this->error        = $file['error'];
        $this->size         = $file['size'];
        $this->extension    = substr($this->fileName, strrpos($this->fileName, '.' ) + 1);
    }
    
    private function checkMimeType($valid)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $ext = array_search(
            $finfo->file($this->tmpName), 
            $valid, 
            true
        );
        
        return $ext;
    }

    private function isPGN()
    {
        if($this->checkMimeType($this->validPGN) && $this->extension == 'pgn') {
            return true;
        }
        
        return false;
    }

    public function upload()
    {
        $fileName = $this->fileName;
        $target   = '../../pgn';

        if (!is_dir($target)) {
            mkdir($target, 0777, true);
        }

        if ($this->isPGN()) {
            if (move_uploaded_file($this->tmpName, $target . DIRECTORY_SEPARATOR . $fileName)) {
                return true;
            }
        }

        return false;
    }

    public function cleanName($str, $replace = array())
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        if (!empty($replace)) {
            $str = str_replace((array) $replace, '-', $str);
        }

        $utf8      = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u'  => 'A',
            '/[ÍÌÎÏ]/u'   => 'I',
            '/[íìîï]/u'   => 'i',
            '/[éèêë]/u'   => 'e',
            '/[ÉÈÊË]/u'   => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u'  => 'O',
            '/[úùûü]/u'   => 'u',
            '/[ÚÙÛÜ]/u'   => 'U',
            '/ç/'         => 'c',
            '/Ç/'         => 'C',
            '/ñ/'         => 'n',
            '/Ñ/'         => 'N',
            '/–/'         => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘\'‹›‚]/u'  => '', // Literally a single quote
            '/[“”«»„]/u'  => '', // Double quote
            '/ /'         => '', // nonbreaking space (equiv. to 0x160)
            '/[(]/u'      => '',
            '/[)]/u'      => ''
        );
        
        $cleanName = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.
        $cleanName = preg_replace(array_keys($utf8), array_values($utf8), $cleanName);
        $cleanName = strtolower(trim($cleanName, '-'));

        return $cleanName;
    }
}
