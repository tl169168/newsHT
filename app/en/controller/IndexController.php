<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\en\controller;

use app\mall\model\MallItemModel;
use app\mall\model\MallModelModel;
use app\portal\model\PortalPostModel;
use cmf\controller\HomeBaseController;
use think\Db;
use think\Session;


class IndexController extends HomeBaseController
{
    function _initialize()
    {
        $cn = url('portal/index/index1', ['id' => 'en']);

        $index = url('en/index/index', []);

        $about = url('en/index/about', ['id' => 'cn']);
        $productmore = url('en/index/productmore', ['id' => 'cn']);
        $equipment = url('en/index/equipment', ['id' => 'cn']);
        $messages = url('en/index/messages', ['id' => 'cn']);
        $jdcgl = url('en/index/jdcg', []);
        $zhaopin = url('en/index/zhaopin', ['id' => 'cn']);
        $zxly = url('en/index/zxly', []);

        $news = url('en/index/news', ['id' => 'cn']);
        $faq = url('en/index/faq', ['id' => 'cn']);
        $contact = url('en/index/contact', ['eid' => '90']);
        $this->assign('cn', $cn);
        $this->assign('zxly', $zxly);
        $this->assign('zhaopin', $zhaopin);
        $this->assign('jdcgl', $jdcgl);
        $this->assign('messages', $messages);
        $this->assign('about', $about);
        $this->assign('index', $index);
        $this->assign('equipment', $equipment);
        $this->assign('productmore', $productmore);
        $this->assign('news', $news);
        $this->assign('faq', $faq);
        $this->assign('contact', $contact);
        $this->_xss_check();
        $this->assign('waitSecond', 3);
        $time = time();
        $this->assign('js_debut', APP_DEBUG ? "?v=$time" : "");
        if (APP_DEBUG) {

        }
        //前后台菜单
        $bt = Db::name('nav_menu')->where('status', 1)->where('nav_id', 1)->where('parent_id', 0)->order('list_order')->select()->toArray();;

        for ($i = 0; $i < count($bt); $i++) {
            $bt[$i]['data'] = Db::name('nav_menu')->where('nav_id', 1)->where('parent_id', $bt[$i]['id'])->order('list_order')->select()->toArray();
        }
//        dump($bt);
        $btt = Db::name('nav_menu')->where('status', 1)->where('nav_id', 2)->where('parent_id', 0)->order('list_order')->select()->toArray();;
        for ($i = 0; $i < count($btt); $i++) {
            $btt[$i]['data'] = Db::name('nav_menu')->where('nav_id', 2)->where('parent_id', $btt[$i]['id'])->order('list_order')->select();

        }
//        dump($bt);

        $this->assign('btt', $btt);
        $this->assign('bt', $bt);
        $this->assign('site_info', cmf_get_option('site_info'));
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        //友情链接
        $yqlj = Db::table('auto_link')
            ->order('list_order')
            ->where('status', 1)
            ->select();
        $this->assign('yqlj', $yqlj);
    }

    private function _xss_check()
    {
        $temp = strtoupper(urldecode(urldecode($_SERVER['REQUEST_URI'])));
        if (strpos($temp, '<') !== false || strpos($temp, '"') !== false || strpos($temp, 'CONTENT-TRANSFER-ENCODING') !== false) {
            die('您当前的访问请求当中含有非法字符,已经被系统拒绝');
        }
        return true;
    }

    public function indexs()
    {
        //前台菜单
        $se = $this->request->param('se');
        Session::set('se', $se);
        $this->assign('se', $se);
//        echo $se;
        $kewordse = Db::name('portal_category')
            ->where('name', '首页幻灯片')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '首页幻灯片')->select();
        $hdp11 = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '合作客户')->select();
        $hdp1 = Db::table('auto_portal_category')
            ->where('parent_id', 222)
            ->order('list_order  desc')
            ->select();

        $this->assign('hdp1', $hdp11);
        $this->assign('hdp', $hdp);

        //公司简介
//        dump($cp);
        $portalPostModel = new PortalPostModel();
        $post = $portalPostModel->where('id', 233)->find();
        $post['url'] = url('en/index/about', ['id' => 85, 'cid' => 269]);
        //联系我们

        //
        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem')
            ->paginate(8);
        $scp = json_decode(json_encode($gsxw), true);

//         dump($scp);exit;
        for ($c = 0; $c < count($scp['data']); $c++) {
//           dump($news['data'][$c]['title']);
            $scp['data'][$c]['url'] = url('en/index/productmore', ['id' => $scp['data'][$c]['id'], 'cid' => $scp['data'][$c]['uid']]);
        }
        $this->assign('scp', $scp['data']);
//公司新闻
        $gsxw4 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.parent_id', 277)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->field('a.id as cid,b.post_id,c.post_title,c.post_titlem,c.post_excerptm,c.post_excerpt,c.create_time,c.thumbnail,b.category_id,c.post_keywords,c.post_keywordsm')
            ->find();

        $gsxw4 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.parent_id', 277)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->field('a.id as cid,b.post_id,c.post_title,c.post_titlem,c.post_excerptm,c.post_excerpt,c.create_time,c.thumbnail,b.category_id,c.post_keywords,c.post_keywordsm')
            ->find();

        $gsxw4['url'] = url('en/index/newsmore', ['id' => $gsxw4['post_id']]);
//        dump($gsxw4); return ;

        $jdcgl = url('en/index/jdcg', ['eid' => 87]);
//        dump($gsxw4); return ;
        $this->assign('jdcgl', $jdcgl);
        $this->assign('gsxw4', $gsxw4);
        //首页视频展示
        $gsxwr = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 271)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();

        for ($y = 0; $y < count($gsxwr); $y++) {
            $gsxwr[$y]['url'] = url('en/index/newsmore', ['id' => $gsxwr[$y]['post_id']]);
        }
        //首页相册
        $gsxwwo = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 270)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();
        for ($m = 0; $m < count($gsxwwo); $m++) {
            $gsxwwo[$m]['url'] = url('en/index/about', ['id' => $gsxwwo[$m]['category_id'], 'eid' => $gsxwwo[$m]['parent_id']]);
        }
        $this->assign('gsxwwo', $gsxwwo);
        //常见问题
        $cjwt = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.post_status', 1)
            ->select();
        $this->assign('cjwt', $cjwt);
//        dump($cjwt);
        $cjwt1 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 1)
            ->where('c.post_status', 1)
            ->find();
        $this->assign('cjwt1', $cjwt1);

        $cpfenlei = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.parent_id', 196)->select();
//        dump($cptj);

        $this->assign('cpfenlei', $cpfenlei);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $eid = $this->request->param('eid');
        $this->assign('eid', $eid);
        $this->assign('gsxwr', $gsxwr);
        $this->assign('post', $post);

        return $this->fetch(':index');
    }

    //首页
    public function index()
    {
        //前台菜单
        $se = $this->request->param('se');
        Session::set('se', $se);
        $this->assign('se', $se);
//        echo $se;
        $kewordse = Db::name('portal_category')
            ->where('name', '首页幻灯片')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '首页幻灯片')->select();
        $hdp11 = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '合作客户')->select();
        $hdp1 = Db::table('auto_portal_category')
            ->where('parent_id', 222)
            ->order('list_order  desc')
            ->select();

        $this->assign('hdp1', $hdp11);
        $this->assign('hdp', $hdp);

        //公司简介
//        dump($cp);
        $portalPostModel = new PortalPostModel();
        $post = $portalPostModel->where('id', 233)->find();
        $post['url'] = url('en/index/about', ['id' => 85, 'cid' => 269]);
        //联系我们

        //
        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem')
            ->paginate(8);
        $scp = json_decode(json_encode($gsxw), true);

//         dump($scp);exit;
        for ($c = 0; $c < count($scp['data']); $c++) {
//           dump($news['data'][$c]['title']);
            $scp['data'][$c]['url'] = url('en/index/productmore', ['id' => $scp['data'][$c]['id'], 'cid' => $scp['data'][$c]['uid']]);
        }
        $this->assign('scp', $scp['data']);
//公司新闻
        $gsxw4 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.parent_id', 277)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->field('a.id as cid,b.post_id,c.post_title,c.post_titlem,c.post_excerptm,c.post_excerpt,c.create_time,c.thumbnail,b.category_id,c.post_keywords,c.post_keywordsm')
            ->find();

        $gsxw4['url'] = url('en/index/newsmore', ['id' => $gsxw4['post_id']]);
//        dump($gsxw4); return ;

        $jdcgl = url('jdcg', ['eid' => 87]);
//        dump($gsxw4); return ;
        $this->assign('jdcgl', $jdcgl);
        $this->assign('gsxw4', $gsxw4);
        //首页视频展示
        $gsxwr = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 271)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();

        for ($y = 0; $y < count($gsxwr); $y++) {
            $gsxwr[$y]['url'] = url('en/index/newsmore', ['id' => $gsxwr[$y]['post_id']]);
        }
        //首页相册
        $gsxwwo = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 270)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();
        for ($m = 0; $m < count($gsxwwo); $m++) {
            $gsxwwo[$m]['url'] = url('en/index/about', ['id' => $gsxwwo[$m]['category_id'], 'eid' => $gsxwwo[$m]['parent_id']]);
        }
        $this->assign('gsxwwo', $gsxwwo);
        //常见问题
        $cjwt = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.post_status', 1)
            ->select();
        $this->assign('cjwt', $cjwt);
//        dump($cjwt);
        $cjwt1 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 1)
            ->where('c.post_status', 1)
            ->find();
        $this->assign('cjwt1', $cjwt1);

        $cpfenlei = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.parent_id', 196)->select();
//        dump($cptj);

        $this->assign('cpfenlei', $cpfenlei);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $eid = $this->request->param('eid');
        $this->assign('eid', $eid);
        $this->assign('gsxwr', $gsxwr);
        $this->assign('post', $post);

        return $this->fetch(':index');
    }
    public function index1()
    {
        //前台菜单
        $se = $this->request->param('se');
        Session::set('se', $se);
        $this->assign('se', $se);
//        echo $se;
        $kewordse = Db::name('portal_category')
            ->where('name', '首页幻灯片')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '首页幻灯片')->select();
        $hdp11 = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '合作客户')->select();
        $hdp1 = Db::table('auto_portal_category')
            ->where('parent_id', 222)
            ->order('list_order  desc')
            ->select();

        $this->assign('hdp1', $hdp11);
        $this->assign('hdp', $hdp);

        //公司简介
//        dump($cp);
        $portalPostModel = new PortalPostModel();
        $post = $portalPostModel->where('id', 233)->find();
        $post['url'] = url('en/index/about', ['id' => 85, 'cid' => 269]);
        //联系我们

        //
        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem')
            ->paginate(8);
        $scp = json_decode(json_encode($gsxw), true);

//         dump($scp);exit;
        for ($c = 0; $c < count($scp['data']); $c++) {
//           dump($news['data'][$c]['title']);
            $scp['data'][$c]['url'] = url('en/index/productmore', ['id' => $scp['data'][$c]['id'], 'cid' => $scp['data'][$c]['uid']]);
        }
        $this->assign('scp', $scp['data']);
//公司新闻
        $gsxw4 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.parent_id', 277)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->field('a.id as cid,b.post_id,c.post_title,c.post_titlem,c.post_excerptm,c.post_excerpt,c.create_time,c.thumbnail,b.category_id,c.post_keywords,c.post_keywordsm')
            ->find();

        $gsxw4['url'] = url('en/index/newsmore', ['id' => $gsxw4['post_id']]);
//        dump($gsxw4); return ;

        $jdcgl = url('jdcg', ['eid' => 87]);
//        dump($gsxw4); return ;
        $this->assign('jdcgl', $jdcgl);
        $this->assign('gsxw4', $gsxw4);
        //首页视频展示
        $gsxwr = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 271)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();

        for ($y = 0; $y < count($gsxwr); $y++) {
            $gsxwr[$y]['url'] = url('en/index/newsmore', ['id' => $gsxwr[$y]['post_id']]);
        }
        //首页相册
        $gsxwwo = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 270)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();
        for ($m = 0; $m < count($gsxwwo); $m++) {
            $gsxwwo[$m]['url'] = url('en/index/about', ['id' => $gsxwwo[$m]['category_id'], 'eid' => $gsxwwo[$m]['parent_id']]);
        }
        $this->assign('gsxwwo', $gsxwwo);
        //常见问题
        $cjwt = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.post_status', 1)
            ->select();
        $this->assign('cjwt', $cjwt);
//        dump($cjwt);
        $cjwt1 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id', 264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 1)
            ->where('c.post_status', 1)
            ->find();
        $this->assign('cjwt1', $cjwt1);

        $cpfenlei = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.parent_id', 196)->select();
//        dump($cptj);

        $this->assign('cpfenlei', $cpfenlei);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $eid = $this->request->param('eid');
        $this->assign('eid', $eid);
        $this->assign('gsxwr', $gsxwr);
        $this->assign('post', $post);

        return $this->fetch(':index');
    }

    public function about()
    {
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
        if ($id == 'cn') {
            $kewordse = Db::name('portal_category')
                ->where('name', '公司概要')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        } else {
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }

        $se = Session::get('se');
        $this->assign('se', $se);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        $feban = Db::table('auto_portal_category')->where('id', $id)->find();

        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', $feban['name'])->find();
        if (empty($hdp)) {
            $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '公司概要')->find();
        }
        //幻灯片
//        dump($hdp);
        //友情链接

        $this->assign('hdp', $hdp);
        //分类

        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id', 268)->select()->toArray();
        for ($i = 0; $i < count($fenlei); $i++) {
            $fenlei[$i]['url'] = url('en/index/about', ['id' => $fenlei[$i]['id'], 'eid' => $eid]);
        }


        //        dump($fenlei);
        $fenlei1 = Db::table('auto_portal_category')->where('id', $id)->find();
//        dump($fenlei);
        $this->assign('fenlei1', $fenlei1);
        $this->assign('fenlei', $fenlei);
        $page = $this->request->param('page', 1);


        if (empty($id) && $eid == 85) {
            $post = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->where('c.delete_time', 0)
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', 269)
                ->find();
//            dump($post);
        } else if ($id == 269) {
            $post = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->where('c.delete_time', 0)
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', $id)
                ->find();
//            dump($post);


        } else {

            $post = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->where('c.delete_time', 0)
                ->limit($page)
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', $id)
                ->paginate(8);

            $this->assign('page', $post->render());
            $news = json_decode(json_encode($post), true);

//             dump($post);exit;
            for ($c = 0; $c < count($news['data']); $c++) {
//           dump($news['data'][$c]['title']);
                $news['data'][$c]['url'] = url('en/index/newsmores', ['id' => $news['data'][$c]['id']]);
            }
//            dump($news);
            $this->assign('news', $news['data']);

        }
//            dump($post);


        $this->assign('bid', $id);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $this->assign('post', $post);

        $this->assign('eid', $eid);
        $this->assign('id', $id);

        if ($id == 269 || empty($id) && $eid = 85) {

            return $this->fetch(':about');

        } else if ($id == 270|| $id==281||$id == 272) {
            return $this->fetch(':xiangce');
        } else {
            return $this->fetch(':video');
        }


    }

    public function jdcg()
    {
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
//        return $eid;

        if ($id == 'cn') {
            $kewordse = Db::name('portal_category')
                ->where('name', '采购供应链')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        } else {
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }

        $se = Session::get('se');
        $this->assign('se', $se);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '采购供应链')->find();
        $this->assign('hdp', $hdp);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id', 273)->select()->toArray();
        for ($i = 0; $i < count($fenlei); $i++) {
            $fenlei[$i]['url'] = url('en/index/jdcg', ['id' => $fenlei[$i]['id'], 'eid' => $eid]);
        }
        $this->assign('fenlei', $fenlei);
        $zhapin = url('en/index/job', ['id' => 'cn']);
        $rencai = url('en/index/job', ['id' => 'ens']);
        $this->assign('zhapin', $zhapin);
        $this->assign('rencai', $rencai);
        $fenlei1 = Db::table('auto_portal_category')->where('delete_time', 0)->where('id', $id)->find();
        if (empty($id) && $eid == 87) {
            $gsxw = Db::table('auto_portal_category_post b,auto_portal_post c')
                ->where('b.post_id=c.id')
                ->where('c.delete_time', 0)
                ->order('b.list_order,c.create_time desc')
                ->where('b.category_id', 274)
                ->find();

        } else {
            $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->order('c.is_top desc,c.recommended')->where('c.delete_time', 0)->where('a.id', $id)
//                ->field('a.id as cid,c.post_title,c.post_content,b.post_id,c.thumbnail,c.create_time')
                ->find();

        }
//        dump($gsxw);
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended', 1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id', 'in', [114, 115])
            ->select();
//        dump($fenlei);
        $this->assign('fenlei1', $fenlei1);
        $this->assign('xinwen', $xinwen);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
//友情链接
        $yqlj = Db::table('auto_link')
            ->order('list_order')
            ->where('status', 1)
            ->select();
        $this->assign('yqlj', $yqlj);
        $cptj = Db::table('auto_mall_category a,auto_mall_item b')
            ->field('b.id,b.thumbnail,b.title,b.titlem,a.namem,a.name')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->where('b.recommended', 1)
            ->paginate(2);
        $this->assign('eid', $eid);
        $this->assign('bid', $id);
        $this->assign('cptj', $cptj);
        $this->assign('gsxw', $gsxw);
        return $this->fetch(':jdcgl');


    }

    public function productmore()
    {



        $cid = $this->request->param('id');
        $eid = $this->request->param('eid');

        $nameid= Db::name('mall_category')
            ->field('name')
            ->where('id',$cid)
            ->find();
//        dump($nameid);
        $kewordse = Db::name('portal_category')
            ->where('name', '酒品专区')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();

        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        //友情链接
        $yqlj=Db::table('auto_link')
            ->order('list_order')
            ->where('status',1)
            ->select();
        $this->assign('yqlj',$yqlj);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '酒品专区')->find();
//        dump($hdp);
        $this->assign('hdp', $hdp);
        $die = $this->request->param('die');

        $fenlei = Db::table('auto_mall_category')->where('delete_time', 0)->order('list_order')->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('en/index/productmore',['id'=>$fenlei[$i]['id'],'eid'=>$eid]);
        }
//       dump($fenlei);
        $this->assign('fenlei', $fenlei);
//        dump($fenlei);
        $id = $this->request->param('id');
        $fenlei1 = Db::table('auto_mall_category')->where('id', $id)->find();


        $this->assign('fenlei1', $fenlei1);

        $cp1 = Db::table('auto_mall_category')->where('delete_time', 0)->where('id', $cid)->find();
        $this->assign('cp1', $cp1);
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        $keyword = $this->request->param('keyword');
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id','in',[114,115])
            ->paginate(9);
//        dump($fenlei);
        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $name = $this->request->param('name');
        $topces=Db::table('auto_mall_category')
            ->where('id',$id)
            ->find();
        $this->assign('topces', $topces);
        $page=$this->request->param('page',1);
        if (!empty($name)){
            $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                ->where('b.title','like','%'.$name.'%')
                ->order('b.list_order')
                ->where('a.id=b.category_id')
                ->limit($page)
                ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                ->paginate(9);

        }else{
            if (empty($id)&&$eid==86) {
                $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                    ->order('b.list_order')
                    ->where('a.id=b.category_id')
                    ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                    ->limit($page)
                    ->paginate(9);

            } else{
                $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                    ->where('b.category_id',$cid)
                    ->order('b.list_order')
                    ->where('a.id=b.category_id')
                    ->limit($page)
                    ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                    ->paginate(9);

            }
        }
        $this->assign('page', $gsxw->render());

        $news = json_decode(json_encode($gsxw),true);

//         dump($news);exit;
        if (!empty($gsxw)){
            for ($c=0;$c<count($news['data']);$c++){
//           dump($news['data'][$c]['title']);
                $news['data'][$c]['url'] =url('en/index/product',['id'=>$news['data'][$c]['id']]);
            }
//
        }
//        dump($news);
        $xurl=url('portal/index/productmore',false,true);
        $se=Session::get('se');
        $this->assign('xurl',$xurl);
        $this->assign('eid',$eid);
        $this->assign('se',$se);
        $this->assign('news', $news['data']);
        $this->assign('name', $name);
        $this->assign('die', $die);
        $this->assign('id', $cid);

        $this->assign('gsxw', $gsxw);
        return $this->fetch(':product');
    }

    public function product(){
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);

        $kewordse=Db::name('portal_category')
            ->where('name', '新闻中心133')->find();
        $se=Session::get('se');

        $this->assign('se',$se);
        $id = $this->request->param('id');
        $cid = $this->request->param('cid');
        $kewords=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('c.id',$id)
            ->find();
//            dump($kewords);
        $this->assign('kewords',$kewords);
        $this->assign('kewordse',$kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '产品中心')->find();
//        dump($hdp);
        $this->assign('hdp', $hdp);
        $die = $this->request->param('cid');

        $uid=Db::table('auto_mall_category')->where('id', $cid)->find();
        $fenlei = Db::table('auto_mall_category')->where('delete_time', 0)->where('parent_id',$uid['parent_id'])->order('list_order')->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('portal/index/product',['id'=>$fenlei[$i]['id']]);
        }
//       dump($fenlei);
        $this->assign('fenlei', $fenlei);
//        dump($fenlei);
        $id = $this->request->param('id');
        $fenlei1 = Db::table('auto_mall_category')->where('id', $cid)->find();


        $this->assign('fenlei1', $fenlei1);

        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->where('b.id',$id)
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->find();
//        dump($gsxw);
        $gsxwt=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->order('b.list_order,c.create_time desc')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.parent_id',$id)->where('c.id','<',$id)
            ->find();
        $gsxwn=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->order('b.list_order,c.create_time desc')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.parent_id',$id)->where('c.id','>',$id)
            ->find();
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.parent_id',203)
            ->paginate(6);
        $gsxw = MallItemModel::get($id);
        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $this->assign('gsxwt', $gsxwt);
        $this->assign('gsxwn', $gsxwn);
        $this->assign('id', $die);
        $this->assign('gsxw', $gsxw);

        return $this->fetch(':product_con_2');
    }

    public function product1()
    {

        $cid = $this->request->param('id');
        $nameid= Db::name('mall_category')
            ->field('name')
            ->where('id',$cid)
            ->find();
//        dump($nameid);
        $kewordse = Db::name('portal_category')
            ->where('name', '产品中心')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();

        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        //友情链接
        $yqlj=Db::table('auto_link')
            ->order('list_order')
            ->where('status',1)
            ->select();
        $this->assign('yqlj',$yqlj);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '产品中心')->find();
//        dump($hdp);
        $this->assign('hdp', $hdp);
        $die = $this->request->param('die');
        $uid=Db::table('auto_mall_category')->where('id', $cid)->find();
        $fenlei = Db::table('auto_mall_category')->where('delete_time', 0)->where('parent_id',$uid['parent_id'])->order('list_order')->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('en/index/product',['id'=>$fenlei[$i]['id']]);
        }
//       dump($fenlei);
        $this->assign('fenlei', $fenlei);
//        dump($fenlei);
        $id = $this->request->param('id');
        $fenlei1 = Db::table('auto_mall_category')->where('id', $id)->find();


        $this->assign('fenlei1', $fenlei1);

        $cp1 = Db::table('auto_mall_category')->where('delete_time', 0)->where('id', $cid)->find();
        $this->assign('cp1', $cp1);
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        $keyword = $this->request->param('keyword');
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id','in',[114,115])
            ->paginate(9);
//        dump($fenlei);
        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $name = $this->request->param('name');
        $topces=Db::table('auto_mall_category')
            ->where('id',$id)
            ->find();
        $this->assign('topces', $topces);
        $page=$this->request->param('page',1);
        if (!empty($name)){
            $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                ->where('b.title','like','%'.$name.'%')
                ->order('b.list_order')
                ->where('a.id=b.category_id')
                ->limit($page)
                ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                ->paginate(9);
        }else{
            if ($cid=='en') {
                $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                    ->order('b.list_order')
                    ->where('a.id=b.category_id')
                    ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                    ->limit($page)
                    ->paginate(9);

            } else{
                $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                    ->where('b.category_id',$cid)
                    ->order('b.list_order')
                    ->where('a.id=b.category_id')
                    ->limit($page)
                    ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                    ->paginate(9);
            }
        }
        $this->assign('page', $gsxw->render());

        $news = json_decode(json_encode($gsxw),true);

        // print_r($nn);exit;
        for ($c=0;$c<count($news['data']);$c++){
//           dump($news['data'][$c]['title']);
            $news['data'][$c]['url'] =url('portal/index/productmore',['id'=>$news['data'][$c]['id'],'cid'=>$news['data'][$c]['uid']]);
        }
//        echo count($news);
//        dump($news['data']); return;
//        dump($gsxw);
        //产品详情页面
        $xurl=url('portal/index/productmore',false,true);
        $se=Session::get('se');
        $this->assign('xurl',$xurl);
        $this->assign('se',$se);
        $this->assign('name', $name);
        $this->assign('die', $die);
        $this->assign('id', $cid);

        $this->assign('gsxw', $news['data']);
        return $this->fetch(':product1');
    }
    public function news()
    {
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
        $this->assign('bid', $id);

        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        if ($id == 'cn') {
            $kewordse = Db::name('portal_category')
                ->where('name', '信息资讯')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        } else {
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }
        $newsmore = url('', []);
        $se = Session::get('se');
        $this->assign('newsmore', $newsmore);
        $this->assign('se', $se);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '信息资讯')->find();
        $this->assign('hdp', $hdp);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id', 260)->select()->toArray();
        for ($i = 0; $i < count($fenlei); $i++) {
            $fenlei[$i]['url'] = url('en/index/news', ['id' => $fenlei[$i]['id']]);
        }
        $this->assign('fenlei', $fenlei);
        $page = $this->request->param('page', 1);
        $fenlei1 = Db::table('auto_portal_category')->where('delete_time', 0)->where('id', $id)->find();

        $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.post_status', 1)
            ->order('c.is_top desc,c.recommended')
            ->where('c.delete_time', 0)
            ->where('a.id|a.parent_id', 277)
            ->field('a.id as cid,c.post_title,c.post_titlem,c.post_content,b.post_id,c.thumbnail,c.create_time,c.post_excerpt,c.post_excerptm')
            ->limit($page)
            ->paginate(4);

        $this->assign('page', $gsxw->render());

        $news = json_decode(json_encode($gsxw), true);

        // print_r($nn);exit;
//        dump($news); return ;
        for ($c = 0; $c < count($news['data']); $c++) {
//           dump($news['data'][$c]['title']);
            $news['data'][$c]['url'] = url('en/index/newsmore', ['id' => $news['data'][$c]['post_id']]);
        }

        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended', 1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id', 'in', [114, 115])
            ->select();
//        dump($fenlei);
        $this->assign('fenlei1', $fenlei1);
        $this->assign('xinwen', $xinwen);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
//友情链接
        $yqlj = Db::table('auto_link')
            ->order('list_order')
            ->where('status', 1)
            ->select();
        $this->assign('yqlj', $yqlj);
        $cptj = Db::table('auto_mall_category a,auto_mall_item b')
            ->field('b.id,b.thumbnail,b.title,b.titlem,a.namem,a.name')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->where('b.recommended', 1)
            ->paginate(2);
        $this->assign('id', $id);
        $this->assign('eid', $eid);
        $this->assign('cptj', $cptj);

        $this->assign('gsxw', $news['data']);
        return $this->fetch(':news');
    }

    public function newsmore()
    {
        //前台菜单

        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);

        $kewordse = Db::name('portal_category')
            ->where('name', '新闻中心133')->find();
        $se = Session::get('se');

        $this->assign('se', $se);
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('c.id', $id)
            ->find();
//            dump($kewords);
        $this->assign('bid', $id);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '信息资讯')->find();
        $this->assign('hdp', $hdp);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id', 260)->select()->toArray();
        for ($i = 0; $i < count($fenlei); $i++) {
            $fenlei[$i]['url'] = url('en/index/news', ['id' => $fenlei[$i]['id']]);
        }
        $this->assign('fenlei', $fenlei);
        $portalPostModel = new PortalPostModel();
        /*  $post            = $portalPostModel->where('id', $id)->find();*/
        $gsxw = $portalPostModel->where('id', $id)->find();

        $gs = Db::table('auto_portal_category_post')->where('post_id', $id)->find();

//        dump($gs);
        $gsxwt = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('b.category_id', $gs['category_id'])
            ->order('c.id desc')
            ->where('b.post_id', '<', $id)
            ->find();
        if (!empty($gsxwt)) {
            $gsxwt['url'] = url('en/index/newsmore', ['id' => $gsxwt['post_id']]);
            $gsxwt['post_title'] = $gsxwt['post_title'];
            $gsxwt['post_titlem'] = $gsxwt['post_titlem'];
        } else {
            $gsxwt['url'] = "";
            $gsxwt['post_title'] = '没有了';
            $gsxwt['post_titlem'] = 'no more';
        }


        $gsxwn = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('b.category_id', $gs['category_id'])->order('c.id asc')->where('b.post_id', '>', $id)
            ->find();
        if (!empty($gsxwn)) {
            $gsxwn['url'] = url('en/index/newsmore', ['id' => $gsxwn['post_id']]);
            $gsxwn['post_title'] = $gsxwn['post_title'];
            $gsxwn['post_titlem'] = $gsxwn['post_titlem'];
        } else {

            $gsxwn['url'] = "";
            $gsxwn['post_title'] = '没有了';
            $gsxwn['post_titlem'] = 'no more';
        }


        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended', 1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.parent_id', 203)
            ->paginate(6);

        $this->assign('xinwen', $xinwen);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $this->assign('gsxwt', $gsxwt);
        $this->assign('gsxwn', $gsxwn);
        $this->assign('eid', $eid);
        $this->assign('id', $id);
        $this->assign('gsxw', $gsxw);

        return $this->fetch(':news_con');
    }

    public function contact()
    {
        //
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        if ($id == 'cn') {
            $kewordse = Db::name('portal_category')
                ->where('name', '联系我们')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        } else {
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }
        $contact1 = url('en/index/contact', ['id' => 254]);
        $feedback = url('en/index/feedback', ['id' => 'cn']);
        $this->assign('feedback', $feedback);
        $this->assign('contact1', $contact1);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->where('id', 236)->order('list_order')->select();
        $this->assign('fenlei', $fenlei);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //友情链接
        $yqlj = Db::table('auto_link')
            ->order('list_order')
            ->where('status', 1)
            ->select();
        $this->assign('yqlj', $yqlj);
        //幻灯片
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id', 266)
            ->find();
//        dump($fenlei);
        $this->assign('xinwen', $xinwen);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '联系我们')->find();
        $this->assign('hdp', $hdp);

        if ($id == 'cn') {
            $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', 254)
                ->find();
        } else {
            $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', $id)
                ->find();
        }
//            dump($gsxw);
        $this->assign('gsxw', $gsxw);
        $se = Session::get('se');
        $this->assign('se', $se);
        $this->assign('eid', $eid);

        return $this->fetch(':contact');


    }

    public function zxly()
    {
        //
        $id = $this->request->param('id');
        $eid = $this->request->param('id');
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        if (empty($id)) {
            $kewordse = Db::name('portal_category')
                ->where('name', '联系我们')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        } else {
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }
        $contact1 = url('en/index/contact', ['id' => 254]);
        $feedback = url('en/index/feedback', ['id' => 'cn']);
        $this->assign('feedback', $feedback);
        $this->assign('contact1', $contact1);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->where('id', 236)->order('list_order')->select();
        $this->assign('fenlei', $fenlei);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //友情链接
        $yqlj = Db::table('auto_link')
            ->order('list_order')
            ->where('status', 1)
            ->select();
        $this->assign('yqlj', $yqlj);
        //幻灯片
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended', 1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id', 'in', [114, 115])
            ->paginate(9);
//        dump($fenlei);
        $this->assign('xinwen', $xinwen);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '联系我们')->find();
        $this->assign('hdp', $hdp);

        if ($id == 'cn') {
            $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', 254)
                ->find();
        } else {
            $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', $id)
                ->find();
        }
//            dump($gsxw);
        $this->assign('gsxw', $gsxw);
        $se = Session::get('se');
        $this->assign('eid', $eid);
        $this->assign('se', $se);

        return $this->fetch(':zxly');


    }

    public function zhaopin()
    {
        //

        $id = $this->request->param('id');

        $eid = $this->request->param('eid');
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        if (empty($id)) {
            $kewordse = Db::name('portal_category')
                ->where('name', '招贤纳士')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        } else {
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }
        $contact1 = url('en/index/contact', ['id' => 254]);
        $feedback = url('en/index/feedback', ['id' => 'cn']);
        $this->assign('feedback', $feedback);
        $this->assign('contact1', $contact1);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id', 279)->select()->toArray();
        for ($i = 0; $i < count($fenlei); $i++) {
            $fenlei[$i]['url'] = url('en/index/zhaopin', ['id' => $fenlei[$i]['id'], 'eid' => $eid]);
        }
        $this->assign('fenlei', $fenlei);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //友情链接
        $yqlj = Db::table('auto_link')
            ->order('list_order')
            ->where('status', 1)
            ->select();
        $this->assign('yqlj', $yqlj);
        //幻灯片
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended', 1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id', 'in', [114, 115])
            ->paginate(9);
//        dump($fenlei);
        $this->assign('xinwen', $xinwen);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '招贤纳士')->find();
        $this->assign('hdp', $hdp);

        $gsxw=Db::table('auto_portal_category_post b,auto_portal_post c')
            ->where('b.post_id=c.id')
            ->order('b.list_order,c.create_time desc')
            ->where('b.category_id',235)
            ->find();

//            dump($gsxw); return;

//            dump($gsxw);
        $this->assign('gsxw', $gsxw);
        $se = Session::get('se');
        $this->assign('eid', $eid);
        $this->assign('se', $se);
        $this->assign('bid', $id);

        return $this->fetch(':zhaopin');


    }

    public function messages()
    {
        //
        $id = $this->request->param('id');
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        if ($id == 'cn') {
            $kewordse = Db::name('portal_category')
                ->where('name', '联系我们')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        } else {
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }
        $contact1 = url('en/index/contact', ['id' => 254]);
        $feedback = url('en/index/feedback', ['id' => 'cn']);
        $this->assign('feedback', $feedback);
        $this->assign('contact1', $contact1);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->where('id', 236)->order('list_order')->select();
        $this->assign('fenlei', $fenlei);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //友情链接
        $yqlj = Db::table('auto_link')
            ->order('list_order')
            ->where('status', 1)
            ->select();
        $this->assign('yqlj', $yqlj);
        //幻灯片
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id', 266)
            ->find();
//        dump($fenlei);
        $this->assign('xinwen', $xinwen);
        $lxwm = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->order('b.list_order,c.create_time desc')->where('a.id', 125)->select();
        $this->assign('lxwm', $lxwm);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '联系我们')->find();
        $this->assign('hdp', $hdp);

        if ($id == 'cn') {
            $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', 254)
                ->find();
        } else {
            $gsxw = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->order('b.list_order,c.create_time desc')
                ->where('a.id', $id)
                ->find();
        }
//            dump($gsxw);
        $this->assign('gsxw', $gsxw);
        $se = Session::get('se');
        $this->assign('se', $se);


        return $this->fetch(':messages');


    }

    public function add()
    {
        $data['username'] = $this->request->param('name');
        if (empty($data['username'])) {
            return $this->error('Please complete');
        }
        $data['phone'] = $this->request->param('tel');
        if (empty($data['phone'])) {
            return $this->error('Please complete');
        }
        if (!preg_match("/^1[34578]\d{9}$/", $data['phone'])) {
            return $this->error('Please fill in the correct mobile phone number！！');
        }
        $data['Email'] = $this->request->param('em');
        if (empty($data['Email'])) {
            return $this->error('Please complete');
        }
        $checkmail = "/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//定义正则表达式
        if (!preg_match($checkmail, $data['Email'])) {                        //用正则表达式函数进行判断
            return $this->error('Please fill in the correct email！！');
        }
        $data['address'] = $this->request->param('add');
        if (empty($data['address'])) {
            return $this->error('Please complete');
        }
        $data['content'] = $this->request->param('con');
        if (empty($data['content'])) {
            return $this->error('Please complete');
        }
        $data['Corporate'] = 111;
        $data['Full'] = 11;
        $data['data'] = date('Y-m-d');
        $str = Db::table('auto_message')->insert($data);
        if ($str) {
            return $this->success('Message successful');
        } else {
            return $this->error("Network error, please try again later");
        }
    }

    public function pages()
    {
        $id = $this->request->param('id');
        $db = Db::table('auto_portal_post')->where('id', $id)->setInc('post_hits');
        if ($db) {
            return $id;
        } else {
            return 0;
        }

    }

    public function pagese()
    {

        $db = Db::table('auto_mess_lo')->where('id', 1)->setInc('login');
        $db = Db::table('auto_mess_lo')->where('id', 1)->setInc('login1');
    }

    public function pageses(){
        $db=Db::table('auto_mess_lo')->where('id',1)->upload(['login1'=>0]);
    }
    public function cppages(){
        $id=$this->request->param('id');
        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->where('b.id',$id)
            ->setInc('b.view_count');
    }
}
