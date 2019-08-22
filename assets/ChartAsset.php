<?

namespace app\assets;

use yii\web\AssetBundle;

class ChartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/chartjs/Chart.bundle.min.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
