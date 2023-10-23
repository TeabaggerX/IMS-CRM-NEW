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
                                <select class="mb-3 form-control form-control-md" name="template" style="height: 38px;">
                                    <option value="NULL">Select Template</option>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="article">
                                <div class="mb-2"><input type="text" class="form-control form-control-md" placeholder="Article 1" name="Ar[]"></div>
                            </div>
                                <div class="mb-2 loading_posts"><img src="img/IMS_loading.gif"></div>
                            <div class="ad">
                                <div class="mb-2"><textarea class="form-control" placeholder="Ad 1" name="Ad[]" style="height: 11vh; font-size: 10px;"></textarea></div>
                            </div>
                                <div class="mb-2 loading_posts_ad"><img src="img/IMS_loading.gif"></div>
                            <div class="prev">
                                <div class="mb-2"><textarea class="form-control" placeholder="Preview Text 1" name="Preview[]" style="height: 11vh; font-size: 10px;"></textarea></div>
                            </div>
                            <div class="d-grid gap-2"><input class="btn btn-success btn-submit" type="submit"></div>
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
<div class="helpButton">
    <a href="https://forms.office.com/r/B02kA2mKmk" target="_blank"><img src="img/help.png?v=2"></a>
</div>
