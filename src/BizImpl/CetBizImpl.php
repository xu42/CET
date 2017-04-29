<?php

namespace Cn\Xu42\Cet\BizImpl;

use Cn\Xu42\Cet\Exception\ArgumentException;

class CetBizImpl
{
    public function query($name, $number)
    {
        if (empty($name) || empty($number)) throw new ArgumentException('姓名和准考证号不能为空');

        $url = 'http://www.chsi.com.cn/cet/query?zkzh=' . $number . '&xm=' . urlencode($name);

        $curlResponse = $this->curlRequest($url);

        return $this->reCet($curlResponse);
    }

    private function curlRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.chsi.com.cn/cet/');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0');
        $curlResponse = curl_exec($ch);
        curl_close($ch);
        return $curlResponse;
    }

    private function reCet($content)
    {
        preg_match_all('/<table(.*?)<\/table>/s', $content, $matches);
        preg_match_all('/(>)(.*?)(<)/s', $matches[0][1], $matches);

        $search = ['<', '>', '：', chr(13) . chr(10)];
        $replaceRes = str_replace($search, '', $matches[0]);

        foreach ($replaceRes as $value) { //去除 数组value 前后的空格
            $arr[] = trim($value);
        }

        foreach (array_filter($arr) as $value) { // 数组key重新排序
            $cetScores[] = $value;
        }

        $scores = [
            'name' => (string)$cetScores[3],
            'school' => (string)$cetScores[7],
            'type' => (string)$cetScores[9],
            'written' => [
                'number' => (string)$cetScores[12],
                'score' => (int)$cetScores[16],
                'listening' => (int)$cetScores[20],
                'reading' => (int)$cetScores[24],
                'translation' => (int)$cetScores[26]
            ],
            'oral' => [
                'number' => (string)$cetScores[29],
                'score' => (string)$cetScores[33]
            ]
        ];

        return $scores;
    }

}
