<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Creative Specifications</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body{
            padding: 10px;
        }
        .btn-info{
            width: 286px;
            margin-bottom: 5px;
            background-color: #007B48;
            border-color: #9ACA3C;
        }
        .btn-info:hover {
            color: #fff;
            background-color: #9ACA3C;
            border-color: #007B48;
        }
        .topButton .btn-info{
            width: 80px;
        }
        .btn-info .text {
            margin-right: auto;
        }
        .button_links {
            margin-right: auto;
            margin-left: auto;
        }
        .button_box {
            width: 100%;
        }
        .redBorder {
            border: #00B7AA 3px solid;
        }
        .cs {
            color: white;
            margin-left: auto;
            margin-right: auto;
            width: 496px;
            font-weight: bold;
        }
        .topButton {
            position: fixed;
            bottom: 0px;
            right: 0px;
            padding: 5px;
            z-index: 1;
        }
        #logo{
            position: absolute;
        }
        .gb_black{
            background-color: black;
        }
        .text-primary {
            color: #007B48 !important;
        }
        .stay {
            position: fixed;
            width: 100%;
            z-index: 1;
        }
        .sticky {
            position: fixed;
            top: 0;
            width: 100%
        }
        .mt75 {
            margin-top: 75px;
        }
        @media (max-width : 770px) {
            #logo{
                position: relative;
            }
            .h3, h3 {
                font-size: 1.25rem;
                margin-top: 10px;
            }
            .topButton {
                right: 100px;
            }
            .mt75 {
                margin-top: 120px;
            }
            body {
                padding: 0px;
            }
        }
    </style>
<div class="topButton">
    <a href="#page-top" class="btn btn-info" onclick="cnangeBordercolor('page-top')">
        <span class="icon text-white-50">
        </span>
        <span class="text">To Top</span>
    </a>
</div>
<body id="page-top">

<div class="card shadow mb-4">
    <div class="card-header stay py-3 gb_black" id="stay">
        <img id="logo" src="img/logo.png"> <div class="h3 mb-2 cs">Ad Specs and Creative Requirements</div>
    </div>
    <div class="card-body">
        <div class="table-responsive mt75">
            <div class="button_box">
                <div class="button_links">
                    <a href="#registrationSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('registrationSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Co-Registration Specs</span>
                    </a>
                    <a href="#newsletterSponsorshipSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('newsletterSponsorshipSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Newsletter Sponsorship Specs</span>
                    </a>
                    <a href="#dedicatedEmailSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('dedicatedEmailSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Dedicated Email Specs</span>
                    </a>
                    <div href="#pushNotificationSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('pushNotificationSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Push Notification Specs</span>
                    </div>
                    <a href="#richTextSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('richTextSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Rich Text Specs</span>
                    </a>
                    <a href="#triggeredEmailSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('triggeredEmailSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Triggered Email Specs</span>
                    </a>
                    <a href="#SMSSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('SMSSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">SMS Specs</span>
                    </a>
                    <a href="#triggeredSMSSpecs" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('triggeredSMSSpecs')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Triggered SMS Specs</span>
                    </a>
                    <a href="#MiniInterstitial" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('MiniInterstitial')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Display - Mini Interstitial</span>
                    </a>
                    <a href="#StaticBanners" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('StaticBanners')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Animated / Static Banners</span>
                    </a>
                    <a href="#PreRoll" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('PreRoll')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Video / Pre-Roll</span>
                    </a>
                    <a href="#DisplayExecution" class="btn btn-info btn-icon-split" onclick="cnangeBordercolor('DisplayExecution')">
                        <span class="icon text-white-50">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <span class="text">Native Display Execution</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-6">

    <div class="card shadow mb-4" id="registrationSpecs">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Co-Registration Specs</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Headline</td>
                                <td>50-75 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Offer Body</td>
                                <td>250 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Image(s)</td>
                                <td>120x60 (animated .gif file format has no loop or frame caps)</td>
                            </tr>
                            <tr>
                                <td>Suppression File</td>
                                <td>MD5 encrypted (Optional, but recommended to eliminate duplicates to your database)</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Lead delivery instructions</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4" id="dedicatedEmailSpecs">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Dedicated Email Specs  </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Creative</td>
                                <td>HTML format</td>
                            </tr>
                            <tr>
                                <td>Subject Line</td>
                                <td>Recommend that you use no more than 9 words and 60 characters</td>
                            </tr>
                            <tr>
                                <td>Suppression File</td>
                                <td>If applicable</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Seed List<br>Landing page URL (Unique tracking by email list recommended)</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <!-- DataTales Example -->
        <div class="card shadow mb-4" id="newsletterSponsorshipSpecs">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Newsletter Sponsorship Specs</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Headline</td>
                                <td>65 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Offer Body</td>
                                <td>400 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Call to Action</td>
                                <td>100 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Image(s)</td>
                                <td>360x180 or 360x240</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Landing page URL (Unique tracking by creative version recommended)</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4" id="pushNotificationSpecs">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Push Notification Specs </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Headline</td>
                                <td>55 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Offer Body</td>
                                <td>150 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Call to Action</td>
                                <td>30 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Image(s)</td>
                                <td>200x200 and 360x180</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Landing page URL (Unique tracking by creative version recommended)</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         
        
    </div>
</div>
<div class="row">

    <div class="col-lg-6">
        <div class="card shadow mb-4" id="richTextSpecs">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rich Text Specs</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Headline</td>
                                <td>65 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Offer Body</td>
                                <td>300 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Call to Action</td>
                                <td>100 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Image(s)</td>
                                <td>200x200</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Landing page URL (Unique tracking by creative version recommended)</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       
       <div class="card shadow mb-4" id="SMSSpecs">
           <div class="card-header py-3">
               <h6 class="m-0 font-weight-bold text-primary">SMS Specs</h6>
           </div>
           <div class="card-body">
               <div class="table-responsive">
                   <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                       <tbody>
                           <tr>
                               <td>Offer Body</td>
                               <td>100 characters (including spaces)</td>
                           </tr>
                           <tr>
                               <td>Additional Elements</td>
                               <td>Landing page URL (Unique tracking by creative version recommended)</td>
                           </tr>
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
        
    </div>

    <div class="col-lg-6">
       <div class="card shadow mb-4" id="triggeredEmailSpecs">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Triggered Email Specs </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Creative</td>
                                <td>HTML format (2-4 creative versions needed)</td>
                            </tr>
                            <tr>
                                <td>Subject Line</td>
                                <td>Recommend that you use no more than 9 words and 60 characters (3-4 options per creative needed)</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Landing page URL (Unique tracking by creative version recommended)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4" id="triggeredSMSSpecs">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Triggered SMS Specs </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Offer Body</td>
                                <td>100 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Landing page URL (Unique tracking by creative version recommended)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4" id="StaticBanners">
 <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Animated / Static Banners</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Placement / Ad Type</th>
                        <th>Platform</th>
                        <th>Ad Unit Size/ Format</th>
                        <th>3rd Party Tags Allowed</th>
                        <th>Image Types</th>
                        <th>Static Back up?</th>
                        <th>Initial Load Max File Size</th>
                        <th>Max File Size</th>
                        <th>Animation Allowed</th>
                        <th>Animation & Looping Limitations</th>
                        <th>Audio Options</th>
                        <th>Audio Limits</th>
                        <th>Frame Rate Limits</th>
                    </tr>
                </thead>
                    <tr>
                        <td>Display</td>
                        <td>Desktop/Tablet</td>
                        <td>728x90</td>
                        <td>Yes</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>39k</td>
                        <td>200k</td>
                        <td>Yes</td>
                        <td>3x, 30 sec</td>
                        <td>UserInitiated on click</td>
                        <td>NA</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Display</td>
                        <td>Desktop/Tablet</td>
                        <td>300x600</td>
                        <td>Yes</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>39k</td>
                        <td>200k</td>
                        <td>Yes</td>
                        <td>3x, 30 sec</td>
                        <td>UserInitiated on click</td>
                        <td>NA</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Display</td>
                        <td>Desktop/Tablet</td>
                        <td>970x250</td>
                        <td>Yes</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>39k</td>
                        <td>200k</td>
                        <td>Yes</td>
                        <td>3x, 30 sec</td>
                        <td>UserInitiated on click</td>
                        <td>NA</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Display</td>
                        <td>Desktop/Tablet</td>
                        <td>970x90</td>
                        <td>Yes</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>39k</td>
                        <td>200k</td>
                        <td>Yes</td>
                        <td>3x, 30 sec</td>
                        <td>UserInitiated on click</td>
                        <td>NA</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Display</td>
                        <td>Desktop/Tablet/Mobile</td>
                        <td>300x250</td>
                        <td>Yes</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>39k</td>
                        <td>200k</td>
                        <td>Yes</td>
                        <td>3x, 30 sec</td>
                        <td>UserInitiated on click</td>
                        <td>NA</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Display</td>
                        <td>Mobile</td>
                        <td>320x50</td>
                        <td>Yes</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>50k</td>
                        <td>50k</td>
                        <td>Yes</td>
                        <td>3x, 30 sec</td>
                        <td>UserInitiated on click</td>
                        <td>NA</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Display</td>
                        <td>Mobile</td>
                        <td>300x50</td>
                        <td>Yes</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>50k</td>
                        <td>50k</td>
                        <td>Yes</td>
                        <td>3x, 30 sec</td>
                        <td>UserInitiated on click</td>
                        <td>NA</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Interstitial</td>
                        <td>Desktop/Tablet/Mobile</td>
                        <td>336x280</td>
                        <td>No (Trackers Only)</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>200k</td>
                        <td>200k</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                    </tr>
                    <tr>
                        <td>Interstitial</td>
                        <td>Desktop/Tablet/Mobile</td>
                        <td>480x320</td>
                        <td>No (Trackers Only)</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>200k</td>
                        <td>200k</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                    </tr>
                    <tr>
                        <td>Interstitial</td>
                        <td>Desktop/Tablet/Mobile</td>
                        <td>550x480</td>
                        <td>No (Trackers Only)</td>
                        <td>HTML5, JPG, GIF</td>
                        <td>Yes</td>
                        <td>200k</td>
                        <td>200k</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>Newsletter</td>
                        <td>728x90</td>
                        <td>No</td>
                        <td>jpeg, gif</td>
                        <td>No</td>
                        <td>39k</td>
                        <td>39k</td>
                        <td>No</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                        <td>NA</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4" id="MiniInterstitial">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Display - Mini Interstitial </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <td>Headline</td>
                                <td>50 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Offer Body</td>
                                <td>150 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Call to Action</td>
                                <td>15 characters (including spaces)</td>
                            </tr>
                            <tr>
                                <td>Image(s)</td>
                                <td>200x200</td>
                            </tr>
                            <tr>
                                <td>Additional Elements</td>
                                <td>Landing page URL (Unique tracking by creative version recommended)</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- DataTales Example -->
<div class="card shadow mb-4" id="DisplayExecution">
 <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Native Display Execution</h6></br>
        <span>Matching the look and feel of the publisher’s page, the Native Display unit lives within the publisher’s content feed. Users are taken directly to the advertiser's site after clicking on the ad unit.</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Preview Image (required)</th>
                        <th>Headline (required)</th>
                        <th>Preview Text (recommended)</th>
                        <th>Advertiser Name (required)</th>
                        <th>Logo (optional)</th>
                    </tr>
                </thead>
                    <tr>
                        <td>JPG, PNG or GIF 800x600px recommended minimum, 3MB max</td>
                        <td>Recommended up to 60 characters, 80 characters max</td>
                        <td>Recommended up to 90 characters, 120 characters max, Availability varies by publisher</td>
                        <td>25 characters max</td>
                        <td>JPG or PNG, 30x100 recommended (30px min height) and Transparent background</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- DataTales Example -->
<div class="card shadow mb-4" id="PreRoll">
 <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Video / Pre-Roll</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Placement / Ad Type</th>
                        <th>Platform</th>
                        <th>Max File Size</th>
                        <th>3rd Party Tags Allowed</th>
                        <th>Video Dimensions</th>
                        <th>Static Back up?</th>
                        <th>Aspect Ratio</th>
                        <th>Video Length</th>
                        <th>Format Accepted</th>
                        <th>Frame Rate</th>
                        <th>Bit Rate</th>
                        <th>Recommended File Size</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                    <tr>
                        <td>Video</td>
                        <td>Desktop/Tablet/Mobile</td>
                        <td>3mb</td>
                        <td>Yes</td>
                        <td>1920×1080 or 1280×720</td>
                        <td>NA</td>
                        <td>4:3 or 16:9</td>
                        <td>:15 or :30 sec</td>
                        <td>.mov, URL</td>
                        <td>0</td>
                        <td>1500 kbps</td>
                        <td>3mb</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>        

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script type="text/javascript">
        function cnangeBordercolor(thisID){
            jQuery('.redBorder').removeClass('redBorder');
            if(thisID != 'page-top'){
                jQuery('#'+thisID).addClass('redBorder');
            }
            var $target  = $(window.location.hash).closest('#'+thisID);
            $('html, body').animate({scrollTop: $target.offset().top}, 1000);
            
        }
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {myFunction()};

        // Get the header
        var header = document.getElementById("stay");

        // Get the offset position of the navbar
        var sticky = header.offsetTop;

        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function myFunction() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }



    </script>
</body>

</html>
