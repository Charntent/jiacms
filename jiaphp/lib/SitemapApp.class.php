<?php

/**
 * CWCMS  sitemap生成文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: SitemapApp.class.php 202 2016-07-08 16:29:08Z Charntent $
 */
class SitemapApp
{
	public $urls = array();
	
    function __construct()
    {
        $this->SitemapApp();
    }
	
    function SitemapApp()
    {
        $this->_baidu_sitemmap_file = ROOT_PATH . '/sitemap.xml';
    }

    function index()
    {  
	   // header("Content-type: application/xml");
        $from = empty($_GET['from']) ? 'baidu' : trim($_GET['from']);
        switch ($from)
        {
            case 'baidu':
			    $hander = $this->_output_baidu_sitemap();
				return $hander;
            break;
        }
    }

    /**
     *    输出baidu sitemap
     *
     *    @author    Charntent
     *    @return    void
     */
    function _output_baidu_sitemap()
    {
       
        return $this->_get_baidu_sitemap();
    }

    /**
     *    获取baidu sitemap
     *
     *    @author    Charntent
     *    @return    string
     */
    function _get_baidu_sitemap()
    {
        $sitemap = "";
        if ($this->_baidu_sitemap_expired())
        {
            /* 已过期，重新生成 */

            /* 获取有更新的项目 */
            $updated_items = $this->_get_updated_items($this->_get_baidu_sitemap_lastupdate());

            /* 重建sitemap */
            $sitemap = $this->_build_baidu_sitemap($updated_items);

            /* 写入文件 */
           $this->_write_baidu_sitemap($sitemap);
        }
        else
        {
            /* 直接返回旧的sitemap */
            //$sitemap = file_get_contents($this->_baidu_sitemmap_file);
        }

        return $sitemap;
    }

    /**
     *    判断baidu sitemap是否过期
     *
     *    @author    Charntent
     *    @return    boolean
     */
    function _baidu_sitemap_expired()
    {
        if (!is_file($this->_baidu_sitemmap_file))
        {
            return true;
        }
        $frequency = 1 * 3600;
        $filemtime = $this->_get_baidu_sitemap_lastupdate();

        return (time() >= $filemtime + $frequency);
    }

    /**
     *    获取上次更新日期
     *
     *    @author    Charntent
     *    @return    int
     */
    function _get_baidu_sitemap_lastupdate()
    {
        return is_file($this->_baidu_sitemmap_file) ? filemtime($this->_baidu_sitemmap_file) : 0;
    }

    /**
     *    获取已更新的项目
     *
     *    @author    Charntent
     *    @return    array
     */
    function _get_updated_items($timeline = 0)
    {
		global $db;
        $timeline && $timeline -= date('Z');
        $limit = 5000;
        $result = array();
        /* 首页 */
	    $result[] = array(
				'url'       => BASEURL,
				'priority'=>'1',
				'lastmod'   => date("Y-m-d"),
				'changefreq'=> 'daily',
		);
		$this->urls[] = BASEURL;
        /* 更新的栏目 */
        $categorys  = $db->t("category")->where(" id>0 and lang='".LANG."' ")->all();
		
		foreach ($categorys as $k => $_v)
		{
			$result[] = array(
				'url'       => convert_catid_to_url($_v['id']),
				'lastmod'   => date("Y-m-d"),
				'changefreq'=> 'daily',
				'priority'  => '1',
			);
			$this->urls[] = convert_catid_to_url($_v['id']);
		}
        

        /* 更新的所有文章 */
       $articles  = $db->t("article")->orderby(" id ")->all();
		foreach ($articles as $k => $_v)
		{
			if($_v['catid']!='' && $_v['catid']>0){
				$result[] = array(
						'url'       => convert_aid_to_url($_v['id'],$_v['catid']),
					'lastmod'   => date("Y-m-d",$_v['createtime']),
					'changefreq'=> 'daily',
					'priority'  => '0.5',
				);
				$this->urls[] = convert_aid_to_url($_v['id'],$_v['catid']);
			}
		}
        

        return $result;
    }

    /**
     *    生成baidu sitemap
     *
     *    @author    Charntent
     *    @param     array $items
     *    @return    string
     */
    function _build_baidu_sitemap($items)
    {
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
       // $sitemap .= "    <url>\r\n        <loc>" . htmlentities(BASEURL, ENT_QUOTES) . "</loc>\r\n        <lastmod>" . date('Y-m-d', time()) . "</lastmod>\r\n        <changefreq>always</changefreq>\r\n        <priority>1</priority>\r\n    </url>";
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                $sitemap .= "\r\n    <url>\r\n        <loc>" . htmlentities($item['url'], ENT_QUOTES) . "</loc>\r\n        <lastmod>{$item['lastmod']}</lastmod>\r\n        <changefreq>{$item['changefreq']}</changefreq>\r\n        <priority>{$item['priority']}</priority>\r\n    </url>";
            }
        }
        $sitemap .= "\r\n</urlset>";
		     
	   return $sitemap;
    }

    /**
     *    写入baidu sitemap文件
     *
     *    @author    Charntent
     *    @param     string $sitemap
     *    @return    void
     */
    function _write_baidu_sitemap($sitemap)
    {
        return file_put_contents($this->_baidu_sitemmap_file, $sitemap);
    }
}
?>