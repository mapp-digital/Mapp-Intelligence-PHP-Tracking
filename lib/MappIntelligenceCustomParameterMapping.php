<?php

/**
 * Class MappIntelligenceCustomParameter
 */
class MappIntelligenceCustomParameterMapping
{
    /**
     * Query parameter string.
     */
    private $queryParameter;

    /**
     * Foo constructor.
     *
     * @param string $qp Name of the query parameter string
     */
    public function __construct($qp)
    {
        $this->queryParameter = $qp;
    }

    /**
     * @param integer $id ID of the custom parameter
     * @return string
     */
    public function with($id)
    {
        return $this->queryParameter . $id;
    }
}
