
<div style="z-index:0;" class="col-lg-12 mb-1">
    <div class="row pb-3" style="background: #fff; border: 1px solid rgba(0,0,0,.125); box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%)">
        <label class="col-form-label col-lg-12"><?=$this->translate[$row][0] ?: $row?> 
            <?php if(array_key_exists($row, $this->validation)):?>
                <?php if(!empty($this->validation[$row]['empty'])):?>
                    <span class="text-danger">*</span>
                <?php endif;?>
            <?php endif;?>
            <span id="swap" style="float:right; cursor:pointer; color: #a8a7a7;"><i class="icon-move-alt1"></i></span>
            <span class="d-block font-weight-light text-secondary"><?=$this->translate[$row][1]?></span>
        </label>
        <div class="col-lg-12">
            <input type="text"
                   name="<?=$row?>" 
                   class="form-control" 
                   placeholder="Enter name..."
                   value="<?=isset($_SESSION['res'][$row]) ? 
                                htmlspecialchars($_SESSION['res'][$row]) : 
                                htmlspecialchars($this->data[$row])?>">
        </div>
    </div>
</div>      