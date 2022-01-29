
<div class="form-group col-lg-12">
    <div class="row">
        <label class="col-form-label col-lg-12"><?=$this->translate[$row][0] ?: $row?>
            <span class="d-block font-weight-light text-secondary"><?=$this->translate[$row][1]?></span>
        </label>
        <div class="col-lg-12 d-flex flex-wrap">
            <div class="d-flex align-items-center justify-content-center mb-1 mr-1" style="width:100px; height:100px; border:2px dotted #8a8a8a" >
                <button type="button" class="btn btn-light w-100 h-100" onclick="document.getElementById('<?=$row?>').click();">
                    <i class="icon-plus2" style="font-size:60px;"></i>
                </button>
                <input type="file" style="display:none;" id="<?=$row?>" name="<?=$row?>[]" multiple/>
            </div>
            <?php if ($this->data[$row]) :?>
                <?php $this-> data[$row] = json_decode($this->data[$row]);?>
                    <?php foreach ($this->data[$row] as $value) :?>
            <div class="d-flex align-items-center justify-content-center mb-1 mr-1" style="width:100px; height:100px; border:2px dotted #8a8a8a" >
                <a href="#">
                    <img class="w-100" src="<?=PATH . UPLOAD_DIR . $value?>" alt="...">
                </a>
            </div>      
           
                     <?php endforeach; ?>
                <?php for($i=0; $i < 2; $i++) { ?>
                    <div class="d-flex align-items-center justify-content-center mb-1 mr-1" style="width:100px; height:100px; border:2px dotted #8a8a8a" ></div>
                <?php } ?>

            <?php else: ?>
                <?php for($i=0; $i < 9; $i++) { ?>
                    <div class="d-flex align-items-center justify-content-center mb-1 mr-1" style="width:100px; height:100px; border:2px dotted #8a8a8a" ></div>
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>
</div>