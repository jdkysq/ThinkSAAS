<?php
defined ( 'IN_TS' ) or die ( 'Access Denied.' );

switch ($ts) {
	
	case "" :
		
		$cateid = intval ( $_GET ['id'] );
		$strCate = $new ['article']->find ( 'article_cate', array (
				'cateid' => $cateid 
		) );
		
		// 列表
		$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
		$url = tsUrl ( 'article', 'cate', array (
				'id' => $cateid,
				'page' => '' 
		) );
		$lstart = $page * 10 - 10;
		
		$arrArticles = $new ['article']->findAll ( 'article', array (
				'cateid' => $cateid,
				'isaudit' => 0 
		)
		, 'addtime desc', 'articleid,userid,cateid,title,gaiyao,path,photo,count_comment,count_recommend,count_view,addtime', $lstart . ',10' );
		
		$articleNum = $new ['article']->findCount ( 'article', array (
				
				'cateid' => $cateid,
				'isaudit' => 0 
		)
		 );
		
		$pageUrl = pagination ( $articleNum, 10, $page, $url );
		
		foreach ( $arrArticles as $key => $item ) {
			$arrArticle [] = $item;
			$arrArticle [$key]['title'] = stripslashes($item['title']);
			$arrArticle [$key] ['user'] = aac ( 'user' )->getOneUser ( $item ['userid'] );
            $arrArticle [$key] ['cate'] = array(
                'cateid'=>$strCate['cateid'],
                'catename'=>$strCate['catename'],
            );
		}
		
		// 推荐阅读
		$arrRecommend = $new ['article']->getRecommendArticle ();
		
		// 一周热门
		$arrHot7 = $new ['article']->getHotArticle ( 7);
		// 一月热门
		$arrHot30 = $new ['article']->getHotArticle ( 30 );
		
		$title = $strCate ['catename'];
		
		// SEO优化
		$sitekey = $strCate ['catename'];
		$sitedesc = $strCate ['catename'] . ' - 文章';
		
		include template ( 'index' );
		
		break;
}