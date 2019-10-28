<?php


namespace App\Service\Communication;

use App\Entity\Analysis;

interface PythonCommunicationInterface
{
    public function startAnalysis(Analysis $analysis);
}
