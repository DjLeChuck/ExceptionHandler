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
    /** Number of lines to show */
    const ADDITIONAL_LINES = 5;

    /** Style for the highlighted line */
    const HIGHLIGHT_STYLE = 'background-color: #FCFFBF;';

    /** Use javascript's effects (with Mootools) or not */
    const USE_JAVASCRIPT = true;

    protected   $thisFilePath,
                $geshi;

    /**
     * Constructor
     *
     * @param string    $thisFilePath   Relative path of this file
     * @param Object    $geshi          The instance of GeSHi
     */
    public function __construct($thisFilePath, GeSHi $geshi = null)
    {
        $this->thisFilePath = $thisFilePath;
        $this->geshi        = $geshi;
    }

    /**
     * Handles exception and display them in a beautiful way
     *
     * @param Exception $e The caughted exception
     */
    public function catchEmAll($e) {
        ob_start();

        $thisFilePath   = $this->thisFilePath;
        $useJavascript  = self::USE_JAVASCRIPT;

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
        $fileContents   = file($file);
        $linesToShow    = array();
        $source         = '';

        // Get 5 lines before and after the defected line
        for ($x = ($line - self::ADDITIONAL_LINES - 1); $x < ($line + self::ADDITIONAL_LINES); $x++) {
            if (!empty($fileContents[$x]))
                $source .= $fileContents[$x];
        }

        return $this->_configureAndLaunchGeshi($line, $source);
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
            $this->geshi->highlight_lines_extra(array(self::ADDITIONAL_LINES + 1));
            $this->geshi->set_highlight_lines_extra_style(self::HIGHLIGHT_STYLE);
            $this->geshi->set_language_path(dirname(__FILE__) . DIRECTORY_SEPARATOR.'geshi'.DIRECTORY_SEPARATOR);

            $this->geshi->start_line_numbers_at($line - self::ADDITIONAL_LINES);
            $this->geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

            return $this->geshi->parse_code();
        } else {
            return '<pre>'.$source.'</pre>';
        }
    }
}
