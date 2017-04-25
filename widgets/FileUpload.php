<?
namespace app\widgets;

use yii\helpers\Html;

class FileUpload extends \dosamigos\fileupload\FileUpload {
	
	public $templatePath;
	
    public function run()
    {
        $input = $this->hasModel()
            ? Html::activeFileInput($this->model, $this->attribute, $this->options)
            : Html::fileInput($this->name, $this->value, $this->options);

        echo $this->render($this->templatePath, ['input' => $input]);

        $this->registerClientScript();
    }
}