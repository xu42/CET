<?php

namespace Xu42\Cet;

class CetScore
{
    /**
     * 查询四六级成绩
     * @param string $number 准考证号
     * @param string $name 姓名
     * @return array
     */
    public function get($number, $name)
    {
        if (is_null($number) || is_null($name)) {
            return null;
        }
        $url = $this->setUrl($number, $name);
        $webPage = $this->getWebPage($url);
        return $this->cetScore($webPage);
    }


    private function setUrl($number, $name)
    {
        return 'http://www.chsi.com.cn/cet/query?zkzh=' . $number . '&xm=' . urlencode($name);
    }

    /**
     * @param string $url 请求的网址
     * @return string 包含成绩信息的网页源码
     */
    private function getWebPage($url)
    {
        if (is_null($url)) {
            return null;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.chsi.com.cn/cet/');
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0');
        $webPage = curl_exec($ch);
        curl_close($ch);
        return $webPage;
    }

    /**
     * @param string $webPage 包含成绩信息的网页源码
     * @return array 个人和四六级成绩等信息的数组（该数组尚不完美）
     */
    private function cetScore($webPage)
    {
        if (is_null($webPage)) {
            return null;
        }

        preg_match_all('/<table(.|\s)*?<\/table>/', $webPage, $matches);
        preg_match_all('/(>)(.|\s)*?(<)/', $matches[0][1], $matches);

        $search = ['<', '>', '：', chr(13) . chr(10)];
        $result = str_replace($search, '', $matches[0]);

        foreach ($result as $value) { //去除 数组value 前后的空格
            $content[] = trim($value);
        }

        foreach (array_filter($content) as $value) { // 数组key重新排序
            $cetScore[] = $value;
        }

        $score = [
            'name' => (string)$cetScore[3],
            'school' => (string)$cetScore[7],
            'type' => (string)$cetScore[9],
            'written' => [
                'number' => (string)$cetScore[12],
                'score' => (int)$cetScore[16],
                'listening' => (int)$cetScore[20],
                'reading' => (int)$cetScore[24],
                'translation' => (int)$cetScore[26]
            ],
            'oral' => [
                'number' => (string)$cetScore[29],
                'score' => (string)$cetScore[33]
            ]
        ];

        return $score;
    }

}
