<?php

namespace Deblan\Csv;

/**
 * class Csv.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class Csv
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
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * @var bool
     */
    protected $isModified = false;

    /**
     * @var string
     */
    protected $render;

    /**
     * Set the value of "delimiter".
     *
     * @param string $delimiter
     *
     * @return Csv
     */
    public function setDelimiter($delimiter)
    {
        if ($this->delimiter !== $delimiter) {
            $this->delimiter = $delimiter;
            $this->isModified = true;
        }

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
        $enclosure = (string) $enclosure;

        if ($this->enclosure !== $enclosure) {
            $this->enclosure = $enclosure;
            $this->isModified = true;
        }

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
        if ($this->endOfLine !== $endOfLine) {
            $this->endOfLine = (string) $endOfLine;
            $this->isModified = true;
        }

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
     * Set the value of "headers".
     *
     * @param array $headers
     *
     * @return Csv
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        if ($this->headers !== $headers) {
            $this->headers = $headers;
            $this->isModified = true;
        }

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
     * Set the value of "charset".
     *
     * @param string $charset
     *
     * @return Csv
     */
    public function setCharset($charset)
    {
        if ($this->charset !== $charset) {
            $this->charset = (string) $charset;
            $this->isModified = true;
        }

        return $this;
    }

    /**
     * Get the value of "charset".
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /*
     * Sets the value of "datas".
     *
     * @param array $datas
     *
     * @return Csv
     */
    public function setDatas(array $datas)
    {
        if ($this->datas !== $datas) {
            $this->datas = $datas;
            $this->isModified = true;
        }

        return $this;
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
     * Appends data.
     *
     * @param array $data
     *
     * @return Csv
     */
    public function appendData(array $data)
    {
        $this->datas[] = $data;
        $this->isModified = true;

        return $this;
    }

    /*
     * Alias of "appendData()".
     *
     * {@inheritdoc self::appendData()}
     */
    public function addData(array $data)
    {
        return $this->appendData($data);
    }

    /*
     * Prepends data.
     *
     * @param array $data
     *
     * @return Csv
     */
    public function preprendData(array $data)
    {
        array_unshift($this->datas, $data);
        $this->isModified = true;

        return $this;
    }

    /**
     * Formats an array data to a CSV string.
     *
     * @param array $data
     *
     * @return array
     */
    protected function formatData(array $data)
    {
        $columns = [];

        foreach ($data as $value) {
            $value = (string) $value;

            if (!empty($this->enclosure)) {
                $value = str_replace($this->enclosure, str_repeat($this->enclosure, 2), $value);
            }

            $value = sprintf('%1$s%2$s%1$s', $this->enclosure, (string) $value);

            $columns[] = $value;
        }

        $data = implode($this->delimiter, $columns);
        $data = $this->encode($data);

        return $data;
    }

    /*
     * Changes the charset if needed.
     *
     * @param string $value
     *
     * @return string
     */
    public function encode($value)
    {
        return mb_convert_encoding(
            $value,
            $this->charset,
            mb_detect_encoding($value, mb_list_encodings())
        );
    }

    /*
     * Renders the CSV.
     *
     * @param string $filename @see file_put_contents
     * @param int $flags @see file_put_contents
     *
     * @return string
     */
    public function render($filename = null, $flags = null)
    {
        if ($this->isModified || empty($this->render)) {
            $lines = [];

            if (!empty($this->headers)) {
                $lines[] = $this->formatData($this->headers);
            }

            foreach ($this->datas as $data) {
                $lines[] = $this->formatData($data);
            }

            $this->render = implode($this->encode($this->endOfLine), $lines);
        }

        $this->isModified = false;

        if ($filename !== null) {
            $content = $this->render;

            if ($flags === FILE_APPEND && file_exists($filename)) {
                $content = $this->endOfLine.$content;
            }
            
            file_put_contents($filename, $content, $flags, $context);
        }

        return $this->render;
    }
}
