<?php

declare(strict_types=1);

namespace RomanShevtsov\OtusComposerPackage;

use Curl\Curl;

class StathamQuote
{
    public function getQuotes()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->get('https://www.litres.ru/journal/luchshie-tsitaty-stetkhema-kharizmatichnogo-favorita-gaia-richi/');
        if ($curl->isSuccess()) {
            $inputHTML = mb_convert_encoding($curl->response, 'HTML-ENTITIES', 'utf-8');
            $dom = new \DomDocument('1.0', 'UTF-8');
            $dom->substituteEntities = TRUE;
            @$dom->loadHTML($inputHTML);
            $xpath = new \DOMXpath($dom);
            $lists = $xpath->query("//ol[contains(@class,'JournalList_list__eyW5Z JournalList_list_ordered__P8bTl')]");
            if ($lists->length > 0) {
                $i = 1;
                foreach ($lists as $k => $list) {
                    if ($k === 0)
                        continue;
                    $li_elements = $list->getElementsByTagName('li');
                    foreach( $li_elements as $li ) {
                        if ($i > 100)
                            continue;
                        echo '#' . $i . ' ' . $li->textContent . "\n\n";
                        $i++;
                    }
                }
                if ($i > 1) {
                    echo "Источник - https://www.litres.ru/journal/luchshie-tsitaty-stetkhema-kharizmatichnogo-favorita-gaia-richi/ \n";
                }
            } else {
                echo "Вероятно источник сломался... \n";
            }
        } else {
            echo $curl->error_code;
        }
        $curl->close();
    }
}

