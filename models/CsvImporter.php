<?

namespace app\models;

use yii\db\ActiveRecord;

class CsvImporter extends ActiveRecord
{
    private $fp;
    private $parseHeader;
    private $header;
    private $delimiter;
    private $length;

    function __construct($fileName, $parseHeader = false, $delimiter = "\t", $length = 8000)
    {
        $this->fp = $this->utf8FopenRead($fileName);
        $this->parseHeader = $parseHeader;
        $this->delimiter = $delimiter;
        $this->length = $length;

        if ($this->parseHeader) {
            $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);
        }
    }

    function __destruct()
    {
        if ($this->fp) fclose($this->fp);
    }

    function get($maxLines = 0)
    {
        //if $maxLines is set to 0, then get all the data
        $data = [];

        if ($maxLines > 0) $lineCount = 0;
        else $lineCount = -1; // so loop limit is ignored

        while ($lineCount < $maxLines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE) {
            if ($this->parseHeader) {
                foreach ($this->header as $i => $heading_i) {
                    $rowNew[$heading_i] = $row[$i];
                }
                $data[] = $rowNew;
            } else {
                $data[] = $row;
            }

            if ($maxLines > 0) $lineCount++;
        }

        return $data;
    }

    function utf8FopenRead($fileName)
    {
        $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
        $handle = fopen("php://memory", "rw");
        fwrite($handle, $fc);
        fseek($handle, 0);

        return $handle;
    }
}