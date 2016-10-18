<?php


namespace DevGroup\MediaStorage\components;


use yii\helpers\ArrayHelper;

class GlideConfigurator
{
    const FIT_CONTAIN = 'contain';
    const FIT_MAX = 'max';
    const FIT_FILL = 'fill';
    const FIT_STRETCH = 'stretch';
    const FIT_CROP = 'crop';

    /**
     * Result configuration
     * @var array
     */
    private $_resultConfig = [];

    /**
     * GlideConfigurator constructor.
     *
     * @param null|int $w
     * @param null|int $h
     * @param string $fit Sets how the image is fitted to its target dimensions
     * @see http://glide.thephpleague.com/1.0/api/size//#fit-fit
     *
     * @param int $q
     * @param null|int $blur
     * @param array $config
     * Other configuration options from glide
     * Will overwrite original options,
     *     i.e. if $this->width = 80 and $this->config[w] = 100 result will be file.jpg?w=100
     * @see http://glide.thephpleague.com/1.0/api/quick-reference/
     *
     * @todo watermark
     */
    public function __construct($w = null, $h = null, $fit = self::FIT_CONTAIN, $q = 90, $blur = null, $config = [])
    {
        $this->optionsWriter('w', $w);
        $this->optionsWriter('h', $h);
        $this->optionsWriter('fit', $fit);
        $this->optionsWriter('q', $q);
        $this->optionsWriter('blur', $blur);
        $this->_resultConfig = ArrayHelper::merge($this->_resultConfig, $config);
    }

    private function optionsWriter($name, $val)
    {
        if (is_null($val) === false) {
            $this->_resultConfig[$name] = $val;
        }
    }

    public function getConfiguration()
    {
        return $this->_resultConfig;
    }

}