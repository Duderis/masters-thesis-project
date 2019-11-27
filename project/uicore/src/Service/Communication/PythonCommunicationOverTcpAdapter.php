<?php

namespace App\Service\Communication;

use App\Entity\Analysis;
use App\Entity\File;
use App\Service\Communication\PythonCommunicationInterface;

class PythonCommunicationOverTcpAdapter implements PythonCommunicationInterface
{
    public function startAnalysis(Analysis $analysis)
    {
        $fp = fsockopen('tensor', 11111, $errno, $errstr, 30);
        if ($fp) {
            /** @var File $file */
            $file = $analysis->getAnalysisTarget();
            fwrite($fp, $file->getName());
            fclose($fp);
        }
    }
}
