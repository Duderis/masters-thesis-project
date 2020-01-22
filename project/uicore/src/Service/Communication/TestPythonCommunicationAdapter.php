<?php


namespace App\Service\Communication;

use App\Entity\Analysis;

class TestPythonCommunicationAdapter implements PythonCommunicationInterface
{

    public function startAnalysis(Analysis $analysis)
    {
        //do nothing
    }

    public function sendBody($body, $operation)
    {
        //do nothing
    }
}
