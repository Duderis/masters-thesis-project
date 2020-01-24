<?php

namespace App\Service\Communication;

use App\Entity\Analysis;
use App\Entity\File;
use App\Service\Communication\PythonCommunicationInterface;

class PythonCommunicationOverTcpAdapter implements PythonCommunicationInterface
{
    const TIMEOUT = 30;
    const PORT = 11111;
    const HOSTNAME = 'tensor';

    const OP_PREPARE_SEG_TRAIN = 'prepare_seg_train';
    const OP_PREPARE_CLASS_TRAIN = 'prepare_class_train';
    const OP_OVERRIDE = 'override';
    const OP_INFER_FILE = 'infer_seg';
    const OP_SEG_TRAIN = 'train_seg';
    const OP_TRAIN_CLASS = 'train_class';
    const OP_INFER_CLASS = 'infer_class';
    const OP_ANALYZE = 'analyze';

    private function openConnection($errno = null, $errstr = null)
    {
        return fsockopen(
            self::HOSTNAME,
            self::PORT,
            $errno,
            $errstr,
            self::TIMEOUT
        );
    }

    public function startAnalysis(Analysis $analysis)
    {
        $conn = $this->openConnection();
        if ($conn) {
            /** @var File $file */
            $file = $analysis->getAnalysisTarget();
            $message = [
                'operation' => self::OP_INFER_FILE,
                'body' => [
                    'target' => $file->getName()
                ]
            ];
            $jsonMessage = json_encode($message);
            fwrite($conn, $jsonMessage);
            fclose($conn);
        }
    }

    public function sendBody($body, $operation)
    {
        $conn = $this->openConnection();
        if ($conn) {
            $message = [
                'operation' => $operation,
                'body' => $body
            ];
            $jsonMessage = json_encode($message);
            fwrite($conn, $jsonMessage);
            fclose($conn);
        }
    }
}
