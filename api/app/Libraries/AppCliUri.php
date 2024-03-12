<?php

namespace App\Libraries;

use Config\Services;

class AppCliUri
{
    protected $segments = [];
    protected $queryParams = [];

    public function __construct()
    {
        $request = Services::request();

        $segmentContainer = [];
        $queryParamContainer = [];

        $arguments = $request->getServer('argv');
        if ($arguments) {
            $argsvString = implode(' ', $arguments);
            $argsContainer = explode('--', $argsvString);
            if ($argsContainer) {
                // assign 1st value to segment
                $segmentString = trim($argsContainer[0]);
                $segmentContainer = explode(' ', $segmentString);

                // assign other to query param
                $queryParamStringz = $argsContainer[1] ?? '';
                $queryParamStringContainer = explode(' ', $queryParamStringz);
            }

            // Segment
            if ($segmentContainer) {
                array_shift($segmentContainer); // Remove index.php
                $this->segments = $segmentContainer;
            }

            // Query Param
            if ($queryParamStringContainer) {
                foreach ($queryParamStringContainer as $queryStringArgument) {
                    if (strpos($queryStringArgument, '=')) {
                        $queryParamArr = explode('=', $queryStringArgument);
                        if ($queryParamArr) {
                            $paramKey = trim($queryParamArr[0]);
                            $paramValue = $queryParamArr[1] ?? '';
                            $this->queryParams[$paramKey] = trim($paramValue);
                        }
                    } else {
                        $paramKey = trim($queryStringArgument);
                        $this->queryParams[$paramKey] = '';
                    }
                }
            }
        }
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    public function getQuerys(): array
    {
        return $this->queryParams;
    }

    public function getQuery(string $key): string
    {
        $queryValue = $this->queryParams[$key] ?? '';
        return $queryValue;
    }
}
