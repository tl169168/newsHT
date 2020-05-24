<?php
use think\Route;

Route::rule([
    'indexs'=>'en/index/index1',

    'index'=>'portal/index/index',
    'about/:eid/:id'=>'portal/index/about',
    'about1/:eid'=>'portal/index/about',
    'productmore/:eid/:id'=>'portal/index/productmore',
    'productmore/:eid'=>'portal/index/productmore',
    'product/:id'=>'portal/index/product',
    'news/:eid'=>'portal/index/news',
    'faq/:eid'=>'portal/index/faq',
    'messages/:eid'=>'portal/index/messages',
    'contact/:eid'=>'portal/index/contact',
    'feedback/:eid'=>'portal/index/feedback',
    'product/:id'=>'portal/index/product',
    'newsmore/:id'=>'portal/index/newsmore',
    'jdcgl1/:eid'=>'portal/index/jdcg',
    'jdcg/:eid/:id'=>'portal/index/jdcg',
    'zhaopin1/:eid'=>'portal/index/zhaopin',
    'zhaopin/:eid/:id'=>'portal/index/zhaopin',
    'zxly'=>'portal/index/zxly',

],'','get|post');