<?php
/**
 * Image converter interface
 *
 * @package go\Upload\Images
 * @author  Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Upload\Images\Convert;

interface Iface
{
    /**
     * Convert image
     *
     * @param string $infile
     *        source file name
     * @param string $outfile
     *        destination file name
     * @throws \go\Upload\Images\Exceptions\SourceFileError
     *         source file is not found
     * @throws \go\Upload\Images\Exceptions\ConvertError
     *         error image convert
     * @throws \go\Upload\Images\Exceptions\SaveError
     *         error saving destination file
     */
    public function convertFile($infile, $outfile);
}
