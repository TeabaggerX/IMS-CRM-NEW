<x-header componentName="Create New Template" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/material-ocean.css">
<style>
#main{
        width: 100%;
        position: relative;
}
#output{
        overflow: auto;
        display: inline-block;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 50%;
}
#result{
        position: absolute;
        width: 50vw;
        height: 100%;
        max-width: 100%;
        border: 1px solid #000;
}
.CodeMirror {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        height: 100%;
        width: 50%;
}
#info{
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 40px;
    right: 40px;
    background-color: green;
    border-radius: 50%;
    color: white;
    text-align: center;
    padding: auto;
    box-shadow: 2px 2px 3px #999;
}
.top-float{
    font-size: 1.5em;
    margin-top:18px;
}
.clear {
    clear: both;
}
@media screen and (max-width: 800px) {
    #info{
        right: 20px;
        bottom: 20px;
        width: 50px;
        height: 50px;
    }
    .top-float{
        font-size: 1.2em;
        margin-top: 15px;
        
    }
    #output {
        top: 50%;
        display: block;
        left:0;
        width: 100%;
        height: 50%;
    }
    #result{
        width: 100vw;
        height: 50vh;
    }
    .CodeMirror{
        height: 50%;
        width: 100%;
    }
}
</style>
<!-- Content Row -->
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST" id="tempForm">
            <div class="form-group">
                <label for="affiliate" class="form-label mt-4">Affiliate</label>
                <input type="text" class="form-control" id="affiliate" placeholder="Affiliate" name="affiliate" required value="<?=$template->affiliate?>">
            </div>
            <div class="form-group">
                <label for="url" class="form-label mt-4">URL</label>
                <input type="text" class="form-control" id="url" placeholder="URL" name="url" value="<?=$template->url?>">
            </div>
            <div class="form-group">
                <label for="url" class="form-label mt-4">API INFO</label>
                <input type="text" class="form-control" id="api" placeholder="API INFO" name="api" value="<?=$template->api?>">
            </div>
            <div class="form-group">
                <label for="url" class="form-label mt-4">Affiliate Id</label>
                <?
                    $dd = new dropdown();
                    $dd->blankFirst = '--Select Affiliate--';
                    $dd->setName('affiliate_id');
                    $dd->setStyle('mb-3 form-control form-control-md');
                    $dd->setOptions($dropDown, $template->affiliate_id);
                    $dd->draw();
                ?>
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" id="affiliate_active" placeholder="" name="affiliate_active" value="<?=$template->affiliate_active?>">
                <span href="#" id="active" class=" btn btn-success<?=$active?> btn-circle">
                    <i class=" fas fa-check"></i>
                </span>
                Active
            </div>
            <div class="color mt-5">
                <label style="vertical-align: super;">Select link color:</label>
                <input type="color" id="colorpicker" name="color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?=$template->color?>" required> 
            </div>
            <div class="form-group">
                <label for="body" class="form-label mt-4">Email Body</label>
                <div id="main">
                    <textarea id="editor" name="temp"><?=$template->body?></textarea>
                </div>
            </div>
            <input type="hidden" id="id" placeholder="" name="id" value="<?=$template->id?>">
            <input type="hidden" id="action" placeholder="" name="action" value="save_temp">
            <input type="hidden" id="saveType" placeholder="" name="saveType" value="<?=$saveType?>">
            <br class="clear">
            <button type="button" id="submit" class="btn btn-primary mb-4"><?=$saveType?></button>
        </form>
    </div>
</div>
<script>
    jQuery('#submit').click(function(){
        jQuery('#editor').val(tinymce.activeEditor.getContent());
        jQuery.post('/module/template/ajax.php',jQuery('#tempForm').serialize()).done(function (res) {
            alertify.alert('Affiliate Saved').set({'frameless':true, 'closable':false, transition:'fade', 'basic':true}); 
            
            setInterval(function(){
                jQuery(location).attr('href', '/index.php?module=template&page=temp');
            }, 1400);
        });
    });

    jQuery('#active').click(function(){
        let active = jQuery('#affiliate_active').val();
        jQuery('#active').removeClass('btn-success');
        jQuery('#active').removeClass('btn-success-not');
        if(active == 1){
            jQuery('#active').addClass('btn-success-not');
            jQuery('#affiliate_active').val('0');
        } else {
            jQuery('#active').addClass('btn-success');
            jQuery('#affiliate_active').val('1');
        }
    });
    tinymce.init({
        selector: 'textarea#editor',
        plugins: 'fullpage advlist lists image media anchor link autoresize code',
        toolbar: 'a11ycheck | blocks bold forecolor backcolor | bullist numlist | link image media anchor | alignleft aligncenter alignright alignjustify | code',
        menubar: 'happy',
        entity_encoding: "raw",
        valid_elements          : '*[*]',
        valid_children          : "+html,+head,+body",
        extended_valid_elements : "html[*],head,body,script[*],style[*],link[*]",
        custom_elements         : "html,head,body,script,style,link",
        apply_source_formatting : false,                //added option
        verify_html : false,     
    });
    // jQuery('#editortxt').val()
</script>