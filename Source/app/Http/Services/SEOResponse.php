<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\DB;

class SEOResponse
{
    private $method;
    private $url;

    function __construct() {
        $this->method   = $_SERVER['REQUEST_METHOD'];
        $this->url      = strtolower(trim(substr($_SERVER['REQUEST_URI'],1)));
    }

    public function send($response){
        if(isset($response->target) && isset($response->target->original) && gettype($response->target->original) == "string" && $this->method === 'GET'){
            list($title,$description,$keywords) = $this->getSEOInfo();
            if($title) {
                $content = $response->target->original;


                $dom = new \DOMDocument('1.0', 'UTF-8');
                libxml_use_internal_errors(true);
                @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                $foundTitle = false;
                $foundDescription = false;
                $foundKeywords = false;
                $titlesList = $dom->getElementsByTagName('title');
                foreach ($titlesList as $titleItem) {
                    $titleItem->nodeValue = $title;
                    $foundTitle = true;
                }

                $metasList = $dom->getElementsByTagName('meta');
                foreach ($metasList as $metaItem) {
                    if ($metaItem->getAttribute('name') == 'description') {
                        $metaItem->setAttribute("content", $description);
                        $foundDescription = true;
                    }

                    if ($metaItem->getAttribute('name') == 'keywords') {
                        $metaItem->setAttribute("content", $keywords);
                        $foundKeywords = true;
                    }
                }

                if (!$foundTitle)
                    $this->addTitle($dom, $title);

                if (!$foundDescription)
                    $this->addMeta($dom, 'description', $description);

                if (!$foundKeywords)
                    $this->addMeta($dom, 'keywords', $keywords);

                $dom->encoding = 'UTF-8';
                echo $dom->saveHTML($dom->documentElement);
            }else{
                $response->send();
            }
        }else{
            $response->send();
        }
    }

    private function getSEOInfo(){
        //return array("title","description","keywords");
        return false;
    }

    private function addMeta($dom,$type,$content){
        $metaElement = $dom->createElement('meta');
        $metaElement->setAttribute('data-n-head', '1' );
        $metaElement->setAttribute('data-hid', $type );
        $metaElement->setAttribute('name', $type );
        $metaElement->setAttribute('content', $content );
        $dom->getElementsByTagName('head')->item(0)->appendChild($metaElement);
    }

    private function addTitle($dom,$content){
        $titleElement = $dom->createElement('title');
        $titleElement->nodeValue = $content;
        $dom->getElementsByTagName('head')->item(0)->appendChild($titleElement);
    }
}
