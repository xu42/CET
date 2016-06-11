<?php

namespace Xu42\Cet;

class CetScore
{
    /**
     * @param string zkzh 准考证号
     * @param string xm 姓名
     * @return string 请求的网址
     */
    private function setUrl($zkzh, $xm)
    {
        $url = null;
        if (is_numeric($zkzh) && (strlen($zkzh) === 15) && is_string($xm)) {
            $url = 'http://www.chsi.com.cn/cet/query?zkzh='.$zkzh.'&xm='.urlencode($xm);
        }
        
        return $url;
    }

    /**
     * @param string url 请求的网址
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
     * @param string webPage 包含成绩信息的网页源码
     * @return string 个人和四六级成绩等信息的数组（该数组尚不完美）
     */
    private function cetScore($webPage)
    {
        if (is_null($webPage)) {
            return null;
        }

        preg_match_all('/<table(.|\s)*?<\/table>/', $webPage, $matches);
        preg_match_all('/(>)(.|\s)*?(<)/', $matches[0][1], $matches);

        $search = array('<','>','：', chr(13).chr(10));
        $result = str_replace($search, '', $matches[0]);

        $content = array();
        foreach ($result as $value) { //去除 数组value 前后的空格
            $content[] = trim($value);
        }

        $content = array_filter($content); // 删除数组空元素

        $cetScore = array();
        foreach ($content as $value) { // 数组key重新排序
            $cetScore[] = $value;
        }

        $score = null;
        if (!isset($cetScore[11])) {
            return $score;
        }
        for ($i=1; $i<count($cetScore); $i+=2) {
            $score[] = $cetScore[$i];
        }
        return $score;
    }

    /**
     * 查询四六级成绩
     * @param string zkzh 准考证号
     * @param string xm 姓名
     * @return array
     */
    public function get($zkzh, $xm)
    {
        if (is_null($zkzh) || is_null($xm)) {
            return null;
        }
        $url = $this->setUrl($zkzh, $xm);
        $webPage = $this->getWebPage($url);
        return $this->cetScore($webPage);
    }
}
