<?php

namespace Deblan\Csv;

/**
 * class Csv.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class CsvParser
{
    /**
     * @var string
     */
    protected $delimiter = ';';

    /**
     * @var string
     */
    protected $enclosure = '"';

    /**
     * @var string
     */
    protected $endOfLine = "\n";

    /**
     * @var array
     */
    protected $datas = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var bool
     */
    protected $hasHeaders = false;

    /**
     * Set the value of "delimiter".
     *
     * @param string $delimiter
     *
     * @return Csv
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = (string) $delimiter;

        return $this;
    }

    /**
     * Get the value of "delimiter".
     *
     * @return array
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Set the value of "enclosure".
     *
     * @param string $enclosure
     *
     * @return Csv
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = (string) $enclosure;

        return $this;
    }

    /**
     * Get the value of "enclosure".
     *
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * Set the value of "endOfLine".
     *
     * @param string $endOfLine
     *
     * @return Csv
     */
    public function setEndOfLine($endOfLine)
    {
        $this->endOfLine = (string) $endOfLine;

        return $this;
    }

    /**
     * Get the value of "endOfLine".
     *
     * @return string
     */
    public function getEndOfLine()
    {
        return $this->endOfLine;
    }

    /**
     * Get the value of "hasHeaders".
     *
     * @return bool
     */
    public function getHasHeaders()
    {
        return $this->hasHeaders;
    }

    /**
     * Set the value of "headers".
     *
     * @param bool $hasHeaders
     *
     * @return Csv
     */
    public function setHasHeaders($hasHeaders)
    {
        $this->hasHeaders = (bool) $hasHeaders;

        return $this;
    }

    /**
     * Get the value of "headers".
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get the value of "datas".
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->datas;
    }

    /*
     * Parses a string.
     *
     * @param string $string
     *
     * @return CsvParser
     */
    public function parseString($string)
    {
        $this->datas = [];
        $this->headers = [];
        $lines = str_getcsv($string, $this->endOfLine);

        foreach ($lines as $key => $line) {
            $data = $this->parseLine($line, $this->hasHeaders && $key === 0);

            if ($data === null) {
                continue;
            }

            if ($this->hasHeaders && $key === 0) {
                $this->headers = $data;
            } else {
                $this->datas[] = $data;
            }
        }

        return $this;
    }

    /*
     * Parses a line.
     *
     * @param string $line
     * @param bool $isHeaders
     *
     * @return array
     */
    public function parseLine($line, $isHeaders = false)
    {
        $line = trim($line);

        if (empty($line)) {
            return null;
        }

        $csv = str_getcsv($line, $this->delimiter, $this->enclosure);

        if (!$isHeaders && $this->hasHeaders && !empty($this->headers)) {
            foreach ($this->headers as $key => $header) {
                $csv[$header] = isset($csv[$key]) ? $csv[$key] : null;
            }
        }

        return $csv;
    }

    /*
     * Parses a file.
     *
     * @param string $filaname
     *
     * @return CsvParser
     */
    public function parseFile($filename)
    {
        return $this->parseString(file_get_contents($filename));
    }
}
