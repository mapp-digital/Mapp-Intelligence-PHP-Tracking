<?php

/**
 * Class MappIntelligenceCLITable
 */
class MappIntelligenceCLITable
{
    const LINE_BREAK = "\n";

    private $maxWidth;
    private $cellWidth;
    private $rows = array();

    /**
     * MappIntelligenceCLITable constructor.
     * @param int $maxWidth
     */
    public function __construct($maxWidth = 72)
    {
        $this->maxWidth = $maxWidth;
        $this->cellWidth = round($this->maxWidth / 18);
    }

    /**
     * @param $txt
     * @param $maxWidth
     *
     * @return array
     */
    private function wordwrap($txt, $maxWidth)
    {
        return explode("\n", wordwrap($txt, $maxWidth, "\n"));
    }

    /**
     * @param string $cell1
     * @param int $width1
     * @param string $cell2
     * @param int $width2
     */
    public function addRow($cell1, $width1, $cell2, $width2)
    {
        $cw1 = $this->cellWidth * $width1;
        $cw2 = $this->cellWidth * $width2;
        $mask = "%-$cw1." . $cw1 . "s %-$cw2." . $cw2 . "s";

        $txt = $this->wordwrap($cell2, $cw2);
        for ($i = 0; $i < count($txt); $i++) {
            if ($i === 0) {
                $this->rows[] = sprintf($mask, $cell1, $txt[$i]);
            } else {
                $this->rows[] = sprintf($mask, '', $txt[$i]);
            }
        }
    }

    /**
     *
     */
    public function addEmptyRow()
    {
        $this->rows[] = '';
    }

    /**
     * @return string
     */
    public function build()
    {
        return implode(self::LINE_BREAK, $this->rows);
    }
}
