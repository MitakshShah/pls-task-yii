<?php

/**
 * @class      HelpController
 *
 * This is the controller that contains the /help actions.
 *
 * @author     Developer
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */
class HelpController extends Controller {

    /**
     * Specifies the action filters.
     *
     * @return array action filters
     */
    public function filters() {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Specifies the access control rules.
     *
     * @return array access control rules
     */
    public function accessRules() {
        return [
            ['allow',  // allow all users to access specified actions.
                'actions' => ['index', 'contact', 'updates'],
                'users'   => ['*'],
            ],
            ['allow', // allow authenticated users to access all actions
                'users' => ['@'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Declares class-based actions.
     *
     * @return array[]
     */
    public function actions() {
        return [
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => [
                'class'     => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ],
        ];
    }

    /**
     * Renders the contact page and handles form submission.
     *
     * @return void
     */
    public function actionContact() {
        $model = new ContactForm();
        if (!empty(Yii::app()->request->getPost('ContactForm'))) {
            $model->attributes = Yii::app()->request->getPost('ContactForm');
            if ($model->validate()) {
                $headers = [
                    'From'     => $model->email,
                    'Reply-To' => $model->email,
                ];
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', ['model' => $model]);
    }

    /**
     * Renders the help videos page.
     *
     * @return void
     */
    public function actionVideos() {
        // Not implemented
    }

    /**
     * Renders the user manuals page.
     *
     * @return void
     */
    public function actionManuals() {
        // Not implemented
    }

    /**
     * Renders the latest updates page based on an RSS feed.
     *
     * @return void
     * @throws FeedException
     */
    public function actionUpdates() {
//        Feed::$userAgent = Yii::app()->params['curlUserAgent'];
//        Feed::$cacheDir = Yii::app()->params['latestUpdatesFeedCacheDir'];
//        Feed::$cacheExpire = Yii::app()->params['latestUpdatesFeedCacheExp'];
//        $feed = Feed::loadRss(Yii::app()->params['latestUpdatesFeedUrl']);
//        $items = [];
//        if (!empty($feed)) {
//            foreach ($feed->item as $item) {
//                $more = ' <a href="' . $item->link . '" target="_blank">Read more</a>';
//                $item->description = trim(str_replace(' [&#8230;]', '...' . $more, $item->description));
//                $item->description = preg_replace('/The post.*appeared first on .*\./', '', $item->description);
//            }
//            $items = $feed->item;
//        }
        // $this->render('updates', ['updates' => $items]);
        $updatedFeeds = $this->getLatestRSSFeed('latestUpdatesFeedUrl',5);
        $this->render('updates',['updates' => $updatedFeeds]);
    }

    public static function getLatestRSSFeed($url, $updates_count_needed) {
        if($url != ''){
            Feed::$userAgent = Yii::app()->params['curlUserAgent'];
            Feed::$cacheDir = Yii::app()->params['latestUpdatesFeedCacheDir'];
            Feed::$cacheExpire = Yii::app()->params['latestUpdatesFeedCacheExp'];
            $feeds = Feed::loadRss(Yii::app()->params[$url]);
            $feeds_arr = (array) $feeds;
            $latest_feeds_arr =  json_decode(json_encode($feeds_arr), true);
            usort($latest_feeds_arr, function($a,$b){
                return $a['pubDate'] - $b['pubDate'];
            });
            $latest_feeds_arr = array_slice($latest_feeds_arr[0]['item'],0, $updates_count_needed, true);

            $latest_feeds_arr = json_decode(json_encode($latest_feeds_arr));
            if (!empty($latest_feeds_arr)) {

                if($updates_count_needed == 1) {
//                  // Updates for slide(s)
                    $more = ' <a href="' . $latest_feeds_arr[0]->link . '" target="_blank">Read more</a>';
                    $latest_feeds_arr[0]->title = trim(str_replace(' [&#8230;]', '...' . $more, $latest_feeds_arr[0]->title));
                    $latest_feeds_arr[0]->description = preg_replace('/The post.*appeared first on .*\./', '', $latest_feeds_arr[0]->description);
                    return $latest_feeds_arr[0];
                } else if($updates_count_needed == 5) {
//                  // Updates for /help/updates page (max 5 updates)
                    $items = [];
                    $index = 0;
                    foreach($latest_feeds_arr as $latest_feed){
                        $more = ' <a href="' . $latest_feed->link . '" target="_blank">Read more</a>';
                        $latest_feed->title = trim(str_replace(' [&#8230;]', '...' . $more, $latest_feed->title));
                        $latest_feed->description = preg_replace('/The post.*appeared first on .*\./', '', $latest_feed->description);
                        $items[$index] = [
                            'link'=>$latest_feed->link,
                            'title'=>$latest_feed->title,
                            'description'=>$latest_feed->description
                        ];
                        $index++;
                    }
                    return $items;
                }
            }
        }
    }

}