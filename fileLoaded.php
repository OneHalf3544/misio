<?php
/**
 * Description of fileLoaded
 *
 * @author OneHalf
 */
class fileLoaded {
    var $FileHasBeenLoaded;
    var $NameFileInput;
    var $tempName;
    var $ResultName;
    var $size;
    function fileLoaded($F, $UploadDir, $NewName = NULL) {
        $this->NameFileInput = $F;
        $this->tempName = $_FILES[$F]['tmp_name'];
        if($NewName == NULL)
            $NewName = basename($_FILES[$F]['name']);
        $this->ResultName = $UploadDir.$NewName;
        $this->size = $_FILES[$F]['size'];
        $this->FileHasBeenLoaded = move_uploaded_file($this->tempName, $this->ResultName);
        if(!$this->FileHasBeenLoaded)
            $this->ResultName = "";
    }
    function getFileExtension() {
        return end(explode('.', $this->ResultName));
    }
    function resizeImage($newX, $newY) {
        if(img_resize($this->ResultName, TempDir.basename($this->ResultName), $newX, $newY) 
            && unlink($this->ResultName)
              && rename(TempDir.basename($this->ResultName), $this->ResultName)
                && chmod($this->ResultName, 0644))
            return true;
        else {
            $this->FileHasBeenLoaded = false;
            return false;
        }
    }
    function getNameForSQL() {
        if($this->FileHasBeenLoaded)
            return '"'.basename($fL->ResultName).'"';
        else
            return 'NULL';
    }
}
?>
