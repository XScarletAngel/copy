<?php
/**
 * User: maChuang
 * blog: martist.cn
 * Date: 2017/5/26
 * Time: 上午10:20
 * 导出Word类
 */

namespace Common\Service;


class ExportWordService
{
    /**
     * User: maChuang
     */
    public function start()
    {
        ob_start();
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"  xmlns:w="urn:schemas-microsoft-com:office:word"  xmlns="http://www.w3.org/TR/REC-html40">
              <head>
                   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                   <xml><w:WordDocument><w:View>Print</w:View></xml>
            </head><body>';
    }

    /**
     * User: maChuang
     */
    public function save($path)
    {
        echo "</body></html>";
        $data = ob_get_contents();
        ob_end_clean();
        $this->wirtefile ($path,$data);
    }

    /**
     * User: maChuang
     */
    public function wirtefile ($fn,$data)
    {
        $fp=fopen($fn,"wb");
        fwrite($fp,$data);
        fclose($fp);
    }

}