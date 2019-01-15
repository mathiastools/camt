<?php

namespace Genkgo\Camt\Camt052\Decoder;

use Genkgo\Camt\Decoder\Message as BaseMessageDecoder;
use Genkgo\Camt\DTO;
use Genkgo\Camt\Camt052\DTO as Camt052DTO;
use \SimpleXMLElement;
use \DateTimeImmutable;
use Genkgo\Camt\Iban;

abstract class Message extends BaseMessageDecoder
{
    /**
     * @param DTO\Message      $message
     * @param SimpleXMLElement $document
     */
    public function addRecords(DTO\Message $message, SimpleXMLElement $document)
    {
        $reports = [];

        $xmlReports = $this->getRootElement($document)->Rpt;
        foreach ($xmlReports as $xmlReport) {
            $report = new Camt052DTO\Report(
                (string) $xmlReport->Id,
                new DateTimeImmutable((string)$xmlReport->CreDtTm),
                $this->getAccount($xmlReport)
            );

            if (isset($xmlReport->RptPgntn)) {
                $report->setPagination(new DTO\Pagination(
                    (string) $xmlReport->RptPgntn->PgNb,
                    ('true' === (string) $xmlReport->RptPgntn->LastPgInd) ? true : false
                ));
            }

            if (isset($xmlReport->AddtlRptInf)) {
                $report->setAdditionalInformation((string) $xmlReport->AddtlRptInf);
            }

            $this->addCommonRecordInformation($report, $xmlReport);
            $this->recordDecoder->addBalances($report, $xmlReport);
            $this->recordDecoder->addEntries($report, $xmlReport);
            $this->recordDecoder->addTransactionSummary($report, $xmlReport);

            $reports[] = $report;
        }

        $message->setRecords($reports);
    }

    /**
     * @param SimpleXMLElement $xmlRecord
     *
     * @return DTO\Account
     */
    protected function getAccount(SimpleXMLElement $xmlRecord)
    {
        if (isset($xmlRecord->Acct->Id->IBAN)) {
            $account = new DTO\IbanAccount(new Iban((string) $xmlRecord->Acct->Id->IBAN));
            $account->setCurrency((string)$xmlRecord->Acct->Ccy);
        
            return $account;
        }

        if (isset($xmlRecord->Acct->Id->BBAN)) {
            return new DTO\BBANAccount((string) $xmlRecord->Acct->Id->BBAN);
        }

        if (isset($xmlRecord->Acct->Id->UPIC)) {
            return new DTO\UPICAccount((string) $xmlRecord->Acct->Id->UPIC);
        }

        if (isset($xmlRecord->Acct->Id->PrtryAcct)) {
            return new DTO\ProprietaryAccount((string) $xmlRecord->Acct->Id->PrtryAcct->Id);
        }

        if (isset($xmlRecord->Acct->Id->Othr)) {
            $xmlOtherIdentification = $xmlRecord->Acct->Id->Othr;
            $otherAccount = new DTO\OtherAccount((string) $xmlOtherIdentification->Id);

            if (isset($xmlOtherIdentification->SchmeNm)) {
                if (isset($xmlOtherIdentification->SchmeNm->Cd)) {
                    $otherAccount->setSchemeName((string) $xmlOtherIdentification->SchmeNm->Cd);
                }

                if (isset($xmlOtherIdentification->SchmeNm->Prtry)) {
                    $otherAccount->setSchemeName((string) $xmlOtherIdentification->SchmeNm->Prtry);
                }
            }

            if (isset($xmlOtherIdentification->Issr)) {
                $otherAccount->setIssuer($xmlOtherIdentification->Issr);
            }

            return $otherAccount;
        }
    }
}
