<?php

/**
 * Run job in backgroung
 */
if (!function_exists('runJobinBackground')) {
    function runJobinBackground($route, $params = [])
    {
        $jobLink = formJobCommand($route, $params, 'background');
        exec($jobLink);
    }
}

/**
 * Run Job
 */
if (!function_exists('runJob')) {
    function runJob($route, $params = [])
    {
        $jobLink = formJobCommand($route, $params);
        exec($jobLink);
    }
}

/**
 * Form job path
 */
if (!function_exists('formJobPath')) {
    function formJobPath($route, $params = [])
    {
        $jobRoutes = ['index.php'];
        $routeString = str_replace('/', ' ', $route);
        if ($routeString) {
            array_push($jobRoutes, $routeString);
        }

        $paramsContainer = [];
        if ($params) {
            foreach ($params as $paramKey => $paramValue) {
                $paramsContainer[] = $paramKey . '=' . $paramValue;
            }
        }

        if ($paramsContainer) {
            $paramsString = '--' . implode(' ', $paramsContainer);
            array_push($jobRoutes, $paramsString);
        }

        $jobLink = implode(' ', $jobRoutes);

        return $jobLink;
    }
}

/**
 * Form job command
 */
if (!function_exists('formJobCommand')) {
    function formJobCommand($route, $params = [], $type = 'foreground')
    {
        $jobRoutes = ['php'];

        $filePath = formJobPath($route, $params);
        if ($filePath) {
            array_push($jobRoutes, $filePath);
        }

        array_push($jobRoutes, '> /dev/null');
        array_push($jobRoutes, '2>&1');

        if ($type == 'background') {
            array_push($jobRoutes, '&');
        }

        $jobLink = implode(' ', $jobRoutes);

        $jobDir = 'cd ' . FCPATH;

        return $jobDir . ' && ' . $jobLink;
    }
}
