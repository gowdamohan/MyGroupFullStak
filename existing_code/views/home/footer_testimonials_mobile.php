<div class="row" style="margin: 0;">
    <div class="container">
        <div class="heading white-heading">
          Testimonials
        </div>
    </div>
</div>

<?php 
$sl= 1;
    foreach ($footer_data as $key => $val) { ?>
        <section class="t-bq-section" id="jasper">
            <div class="t-bq-wrapper t-bq-wrapper-boxed">
                <div class="t-bq-quote t-bq-quote-jasper">
                    <div class="t-bq-quote-jasper-pattern">
                        <div class="t-bq-quote-jasper-qmark">
                            &#10077;
                        </div>
                    </div>
                    <div class="t-bq-quote-jasper-userpic">
                        <a href="#" onclick="open_imagepreview(<?php echo $sl ?>)" id="pop">
                          <img style="width: 100%; border-radius: 50%; height: 70px;" id="imageresource<?php echo $sl ?>" src="<?php echo $this->filemanager->getFilePath($val->image) ?>" alt="...">
                        </a>
                    </div>

                    <div class="t-bq-quote-jasper-base">
                        <blockquote class="t-bq-quote-jasper-text" cite="Strugatsky Brothers">
                            <?php  echo $val->content; ?>
                        </blockquote>
                        <div class="t-bq-quote-jasper-meta">
                            <div class="t-bq-quote-jasper-meta-info">
                                <div class="t-bq-quote-jasper-author"><cite><?php  echo $val->title; ?></cite></div>
                                <div class="t-bq-quote-jasper-source"><span> <?php  echo $val->tag_line; ?> </span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
?>




<style type="text/css">
    blockquote {
        padding: 0;
        margin: 0;
    }

section.t-bq-section {
    padding: 10px;
    margin-bottom: 20px;
}
.heading {
    text-align: center;
    color: #4c372f;
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 20px;
    text-transform: uppercase;
    border: 1px solid #ccc;
    border-radius: 10px;
     background: #4cf8e6;
}
.t-bq-wrapper.t-bq-wrapper-boxed {
    max-width: 576px;
    margin: 0 auto;
}

.t-bq-wrapper.t-bq-wrapper-fullwidth {
    max-width: 100%;
}


.t-bq-quote-jasper {
    position: relative;
    box-shadow: 2px 2px 25px #cecece;
    border-radius: 10px;
}

.t-bq-quote-jasper .t-bq-quote-jasper-pattern {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    height: 80px;
    align-items: center;
    justify-content: flex-start;
    background: url("https://raw.githubusercontent.com/taviskaron/t-div-blockquotes/main/img/hexabump.png") repeat;
    border-radius: 10px 10px 0 0;
}

.t-bq-quote-jasper .t-bq-quote-jasper-pattern .t-bq-quote-jasper-qmark {
    flex-basis: 100px;
    font-family: Garamond, Georgia, "Times New Roman", serif;
    font-size: 60pt;
    color: #999999;
    text-align: center;
    height: 80px;
    line-height: 90pt;
    -moz-user-select: none;
    -ms-user-select: none;
    -webkit-user-select: none;
    user-select: none;
}

.t-bq-quote-jasper .t-bq-quote-jasper-userpic {
    position: absolute;
    top: 45px;
    left: calc(50% - 35px);
    width: 70px;
    height: 70px;
    background-size: cover;
    border-radius: 50%;
}

.t-bq-quote-jasper .t-bq-quote-jasper-base {
    flex-basis: calc(100% - 80px);
    background: #ded3d3;
    padding: 60px 30px 50px 100px;
    font-size: 11pt;
    line-height: 1.62em;
    border-radius: 0 0 10px 10px;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta {
    margin-top: 30px;
    padding-top: 10px;
    border-top: 2px dotted #777777;
    text-align: center;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-author,
.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-source {
    color: #777777;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-author {
    font-style: normal;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    font-size: 10pt;
    font-weight: bold;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-author cite {
    font-style: normal;
}

.t-bq-quote-jasper .t-bq-quote-jasper-meta .t-bq-quote-jasper-source {
    font-size: 9pt;
}

@media screen and (max-width: 768px) {

    .t-bq-quote-jasper .t-bq-quote-jasper-base {
        padding-left: 30px;
    }
}

</style>