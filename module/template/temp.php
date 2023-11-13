<style>
    .alertify .ajs-dialog {
        min-height: 51px;
        max-width: 264px;
        color: white;
        margin: 1% auto;
        background-color: green;
        text-align: center;
    }
</style>
<div class="card shadow mb-4">
    <div class="card-header py-3 text-right">
        <!--<h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>-->
        <a href="index.php?module=template&page=temp_add_edit" class="btn btn-secondary">                                        
            <span class="text">Add New Template</span>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Affiliate</th>
                        <th>Last Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $temps = new templates();
                    $temps = $temps->getWhere(['del' => 0], 'affiliate ASC');
                    // print_r($temps);
                    foreach ($temps as $k) {
                        // print_r($k);
                        $active = '';
                        if($k->affiliate_active == 0 || $k->affiliate_active == ''){
                            $active = '-not';
                        }
                        echo '
                        <tr id="row_'.$k->id.'">
                        <input type="hidden" class="form-control" id="'.$k->id.'_affiliate" placeholder="" name="affiliate" value="'.$k->affiliate.'">
                            <td>'.$k->affiliate.'</td>
                            <td>'.date('Y-m-d',strtotime($k->updated_at)).'</td>
                            <td>
                                <a href="index.php?module=template&page=temp_add_edit&id='.$k->id.'" class="btn btn-info btn-circle" alt="edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <input type="hidden" class="form-control" id="'.$k->id.'" placeholder="" name="affiliate_active" value="'.$k->affiliate_active.'">
                                <span id="active" data="'.$k->id.'" class="activeFlag btn btn-success'.$active.' btn-circle">
                                    <i class=" fas fa-check"></i>
                                </span>
                                <button type="submit" data="'.$k->id.'" class="deleteFlag btn btn-danger btn-circle" alt="delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>';
                    }
                    ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<input id="backbuttonstate" type="text" value="0" style="display:none;" />
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ibackbutton = document.getElementById("backbuttonstate");
    setTimeout(function () {
        if (ibackbutton.value == "0") {
            ibackbutton.value = "1";
        } else {
            jQuery(".custom-select").val(10);
        }
    }, 200);
}, false);


// let table = new DataTable('#dataTable');
    
    jQuery('.deleteFlag').click(function(){  
        var id = jQuery(this).attr('data');
        var affiliate = jQuery('#'+id+'_affiliate').val();
        alertify.confirm('DELETE THIS?', 'Are you sure you want to delete '+affiliate+'?', function(){ 
            jQuery.post('/module/template/ajax.php',{'action':'delete_temp','id':id}).done(function (res) {
                // alert('saved');
                alertify.alert('Affiliate '+res.success+' was DELETED').set({'frameless':true, 'closable':false, transition:'fade', 'basic':true}); 
                jQuery('#row_'+id).hide();
                setInterval(function(){
                    alertify.alert().close(); 
                }, 1400);
            });
         }, function(){ }).set({transition:'fade'});

    });
    jQuery('.activeFlag').click(function(){
        var isactive = 0;
        var id = jQuery(this).attr('data');
        var active = jQuery('#'+id).val();
        jQuery(this).removeClass('btn-success');
        jQuery(this).removeClass('btn-success-not');
        if(active == 1){
            jQuery(this).addClass('btn-success-not');
            jQuery('#'+id).val('0');
        } else {
            jQuery(this).addClass('btn-success');
            jQuery('#'+id).val('1');
            isactive = 1;
        }
        jQuery.post('/module/template/ajax.php',{'action':'active_temp','affiliate_active':isactive,'id':id}).done(function (res) {
         });
    });
</script>