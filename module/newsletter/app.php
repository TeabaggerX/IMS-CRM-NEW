<form action="" id="nlApp" name="nlApp">
<div class="content p-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <p><b>First select the email template you'd like to use, and then fill in each respective field. For articles, input the full article URL. For advertisements, insert the code snippet given by HasOffers. For Featured URL, insert the URL you want the Featured Text to link to. For Featured Text, Type the words you would like on Display at the top of your Featured Newsletter.</b></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 p-4">
                <div class="col">
                    <div class="form-box">
                        <form class="c-form" id="inputForm">
                            <input type="hidden" name="form_url" value="" />
                            <div class="form-group">
                            <?
                                $dd = new dropdown();
                                $dd->blankFirst = '--Select Template--';
                                $dd->setName('template');
                                $dd->setStyle('mb-3 form-control form-control-md templateDropdown');
                                $dd->setOptions($dropDown);
                                $dd->draw();
                            ?>
                            <input type="hidden" id="affiliate_id_encoded" name="affiliate_id_encoded" value="" />
                            </div>
                            <div class="article">
                            </div>
                                <div class="mb-2 loading_posts"><img src="img/IMS_loading.gif"></div>
                            <div class="ad">
                            </div>
                                <div class="mb-2 loading_posts_ad"><img src="img/IMS_loading.gif"></div>
                            <div class="d-grid gap-2"><input id="submit" class="btn btn-success btn-submit" type="button" value="submit"></div>
                            <br>
                            <div class="d-grid gap-2"><input class="btn btn-success btn-unpublish" type="submit" value="Unpublish All Posts"></div>
                            <div class="mb-2 loading_unpublish"><br><img src="img/unpublishing.gif"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8 p-4">
                <div class="Display">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2 loading_html"><button class="btn btn-success btn-copy" onclick="location.reload(true)"> Refresh the page </button></div>
                            <div class="card mb-4 scroll-box html_pre" style="height: 100%;">
                                <p class="small"></p><button class="btn btn-success btn-copy"> Copy HTML to clipboard </button><br>
                                <section class="form-control" id="html" name="test">Please enter a valid template.</section>
                                <p class="small"></p><button class="btn btn-success btn-copy"> Copy HTML to clipboard </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="helpButton">
    <a href="https://forms.office.com/r/B02kA2mKmk" target="_blank"><img src="img/help.png?v=2"></a>
</div>

<script>
        jQuery('.loading_unpublish').hide();
        jQuery('.btn-unpublish').hide();
        jQuery('.loading_posts').hide();
        jQuery('.loading_html').hide();
        jQuery('.loading_posts_ad').hide();
        
        jQuery("#submit").click(function(){
            jQuery.post('/module/newsletter/ajax.php',{'action':'get_temp','form':jQuery('#nlApp').serialize()}).done(function (res) {
                jQuery('#html').html(res);
                tinymce.activeEditor.setContent(res);
            });

        });

        jQuery(".templateDropdown").change(function(){
            jQuery('.ad').html('');
            jQuery('.article').html('');
            var result = jQuery(this).val().split('|');
            var temp_id = result[0];
            var affID = result[1];
            var affEncodedID = result[2];
            jQuery('#affiliate_id_encoded').val(affEncodedID);

            jQuery.post('/module/newsletter/ajax.php',{'action':'get_offers','id':affID,'temp_id':temp_id}).done(function (res) {
                jQuery('.ad').html(res);
            });

            jQuery.post('/module/newsletter/ajax.php',{'action':'get_drafts','id':affID,'temp_id':temp_id}).done(function (res) {
                jQuery('.article').html(res);
            });
        });
        jQuery('.templateDropdown').select2();
        
        tinymce.init({
            selector: 'section#html',
            plugins: 'a11ychecker advcode advlist lists image media anchor link autoresize code',
            toolbar: 'a11ycheck | blocks bold forecolor backcolor | bullist numlist | link image media anchor | alignleft aligncenter alignright alignjustify | code',
            menubar: 'happy',
            verify_html:false,
            cleanup: false,
            valid_children : "+body[style]"
        });
</script>