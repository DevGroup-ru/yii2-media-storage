<?php


namespace DevGroup\MediaStorage\components;


use DevGroup\MediaStorage\models\MediaRoute;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

class MediaRule implements UrlRuleInterface
{

    /**
     * Parses the given request and returns the corresponding route and parameters.
     *
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     *
     * @return array|boolean the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)
    {

        $url = trim($request->pathInfo, '/');
        $condition = ['url' => $url];
        $record = MediaRoute::findOne($condition);
        if (null !== $record) {
            return ['/media/file/send', ['mediaId' => $record->media_id, 'config' => $record->params]];
        }
        return false;
    }

    /**
     * Creates a URL according to the given route and parameters.
     *
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     *
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     * @todo 1) check if file exist 2) url from config (web acceptable, glide or current controller)
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route !== '/media/file/send' || false === isset($params['mediaId'])) {
            return false;
        }
        $condition = ['media_id' => $params['mediaId'],];
        if (ArrayHelper::keyExists('config', $params)) {
            $condition['params'] = Json::encode(ArrayHelper::remove($params, 'config'));
        }
        $url = MediaRoute::find()->select('url')->where($condition)->scalar();
        if (false !== $url) {
            unset($params['mediaId']);
            return $url . (count($params) > 0 ? '?' . http_build_query($params) : '');
        }
        return false;
    }
}