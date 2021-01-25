<?php

/**
 * Class MappIntelligenceBasic
 */
abstract class MappIntelligenceBasic
{
    protected $filterQueryParameter = true;

    /**
     * @param array $params
     * @param string $key
     * @return array
     */
    private function getParameterList($params, $key)
    {
        $data = array();

        foreach ($params as $id => $value) {
            $data[$key . $id] = ((is_array($value)) ? implode(';', $value) : $value);
        }

        return $data;
    }

    /**
     * @return array
     */
    final public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    final public function getQueryParameter()
    {
        $queryList = $this->getQueryList();
        $data = $this->getData();
        $queryParameters = array();

        foreach ($queryList as $property => $queryParameter) {
            if (array_key_exists($property, $data)) {
                if (is_array($data[$property])) {
                    $queryParameters = array_merge(
                        $queryParameters,
                        $this->getParameterList($data[$property], $queryParameter)
                    );
                } else {
                    $queryParameters[$queryParameter] = $data[$property];
                }
            }
        }

        return $this->filterQueryParameter ? array_filter($queryParameters) : $queryParameters;
    }

    /**
     * @return array
     */
    abstract protected function getQueryList();
}
