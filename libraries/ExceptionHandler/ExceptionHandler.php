<?php

/**
 * An exceptions handler.
 *
 * Requires PHP 5.3+
 *
 * @author  DE BONA Vivien <debona.vivien@gmail.com>
 * @version 1.0
 */
class ExceptionHandler
{
    /** Style for the highlighted line */
    const HIGHLIGHT_STYLE = 'background-color: #FCFFBF;';

    protected   $thisFilePath,
                $geshi,
                $additionnalLines,
                $useJavascript;

    /**
     * Constructor
     *
     * @param string    $thisFilePath       Relative path of this file
     * @param Object    $geshi              The instance of GeSHi
     * @param int       $additionnalLines   Number of lines to show
     * @param Boolean   $useJavascript      Use or not javascript
     */
    public function __construct($thisFilePath, GeSHi $geshi = null, $additionnalLines = 5, $useJavascript = true)
    {
        $this->thisFilePath     = $thisFilePath;
        $this->geshi            = $geshi;
        $this->additionnalLines = $additionnalLines;
        $this->useJavascript    = $useJavascript;

        set_exception_handler(array($this, 'catchEmAll'));
    }

    /**
     * Handles exception and display them in a beautiful way
     *
     * @param Exception $e The caughted exception
     */
    public function catchEmAll($e) {
        ob_start();

        $thisFilePath   = $this->thisFilePath;
        $useJavascript  = $this->useJavascript;

        $message    = $e->getMessage();
        $file       = $e->getFile();
        $line       = $e->getLine();
        $trace      = $e->getTrace();
        $exception  = get_class($e);
        $compteur   = 1;
        $code       = self::_traceException($line, $file);

        include 'templates/top.html';

        // Trace the main Exception
        include 'templates/middle.html';
        // ... then loop each trace
        foreach ($trace as $e) {
            $e          = (object) $e;
            $message    = '';
            $file       = $e->file;
            $line       = $e->line;
            $code       = $this->_traceException($line, $file);
            $compteur++;
            include 'templates/middle.html';
        }

        include 'templates/bottom.html';

        ob_end_flush();
    }

    /**
     * Traces the exception
     *
     * @param   int     $line   The line concerned by the exception
     * @param   string  $file   The file concerned by the exception
     *
     * @return  string  The colored code
     */
    protected function _traceException($line, $file)
    {
        try {
            $fileContents   = @file($file);
            $linesToShow    = array();
            $source         = '';

            // Get 5 lines before and after the flawed line
            for ($x = ($line - $this->additionnalLines - 1); $x < ($line + $this->additionnalLines); $x++) {
                if (!empty($fileContents[$x]))
                    $source .= $fileContents[$x];
            }

            return $this->_configureAndLaunchGeshi($line, $source);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Configures and launches GeSHi
     *
     * @param   int     $line   The line concerned by the exception
     * @param   string  $file   The file concerned by the exception
     *
     * @return  string  The colored code
     */
    protected function _configureAndLaunchGeshi($line, $source)
    {
        if (!empty($this->geshi)) {
            $this->geshi->set_language('php');
            $this->geshi->set_source($source);

            $this->geshi->set_header_type(GESHI_HEADER_NONE);
            $this->geshi->highlight_lines_extra(array($this->additionnalLines + 1));
            $this->geshi->set_highlight_lines_extra_style(self::HIGHLIGHT_STYLE);
            $this->geshi->set_language_path(dirname(__FILE__) . DIRECTORY_SEPARATOR.'geshi'.DIRECTORY_SEPARATOR);

            $this->geshi->start_line_numbers_at($line - $this->additionnalLines);
            $this->geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

            return $this->geshi->parse_code();
        } else {
            return '<pre>'.$source.'</pre>';
        }
    }
}
